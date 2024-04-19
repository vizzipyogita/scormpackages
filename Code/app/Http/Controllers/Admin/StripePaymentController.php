<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\User;
use App\Models\Subscription;
use Session;
use Stripe;
use Exception;
use App\Models\CourseCategory;
use App\Models\SubscriptionPlans;

class StripePaymentController extends Controller
{
    var $data = array();
    private $OrganizationModel;
    private $CourseCategoryModel;
    private $SubscriptionPlansModel;

    public function __construct()
    {
        $this->OrganizationModel = new Organization();
        $this->UserModel = new User();
        $this->SubscriptionModel = new Subscription();
        $this->CourseCategoryModel = new CourseCategory();
        $this->SubscriptionPlansModel = new SubscriptionPlans();
    }

    public function upgrade(Request $request)
    {
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');
        // echo $loggedUserOrganizationId; exit;
        //get organization settings
        $settings = $this->OrganizationModel->GetOrganizationSettings($loggedUserOrganizationId);
        
        if(!$settings){
            //Save Organization Settings
            $settingsInput['user_license'] = 1;
            $settings = $this->OrganizationModel->SaveOrganizationSettings($settingsInput, $loggedUserOrganizationId); 
        }

        //Plan details
        $loggedUserCountryId = $request->session()->get('loggedUserCountryId');
        $plans = $this->SubscriptionPlansModel->GetSubscriptionPlansByCountryId($loggedUserCountryId);
        $plans = $plans->toArray();
        // echo $request->session()->get('currency');exit;
        $this->data['pageTitle'] = "Payment";	
        $this->data['activePlanId'] = isset($settings) ? $settings->sub_plan_id : 0;
        $this->data['subPalnType'] = isset($settings) ? $settings->sub_paln_type : '';
        $this->data['isSubscription'] = isset($settings) ? $settings->is_subscription : 0;
        $this->data['plans'] = $plans;
        
        return view('admin.payment.upgrade', $this->data);
    }

    public function payment(Request $request)
    {
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');
        $currency = $request->session()->get('currency');

        $planId = $request->plan_id;
        $isYearlyPlan = isset($request->is_yearly_plan) ? $request->is_yearly_plan : 0;
        $planAmount = 0;
        $planType = 'M';

        //get plan details
        $plan = \DB::table('subscription_plans')->where('id', $planId)->first();
        if($isYearlyPlan == 1){
            $planAmount = $plan->yearly_plan_amount;
            $planType = 'Y';
        }else{
            $planAmount = $plan->monthly_plan_amount;
            $planType = 'M';
        }

        if($currency == '&#8377;'){
            $planAmount = getCurrencyWiseAmount($currency, $planAmount);
        }

        $this->data['pageTitle'] = "Payment";	
        $this->data['planAmount'] = $planAmount;
        $this->data['planType'] = $planType;
        $this->data['planId'] = $planId;

        if($request->ajax())
		{
			$response = array('status'=>'success', 'message'=>'', 'view'=>view('admin.payment.payment_modal', $this->data)->render());
			return response()->json($response);
		}
      
        return view('admin.payment.payment_modal',$this->data);
    }

    public function paymentPost(Request $request)
    {
        try{
            //Get logged user Id
            $loggedUserId = $request->session()->get('LoggedUserId');
            $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');
            $currency = $request->session()->get('currency');
            $currencyCode = strtolower($request->session()->get('currencyCode'));
            $loggedUserCountryId = $request->session()->get('loggedUserCountryId');
            
            $planId = $request->plan_id;
            $userLicense = 1;
            if($planId == 1){
                $userLicense = 50;
            }elseif($planId == 2){
                $userLicense = 100;
            }elseif($planId == 3){
                $userLicense = 500;
            }else{
                $userLicense = 2000;
            }

            $user = $this->UserModel->GetUserDetails($loggedUserId);

            $subscriptionPlan = isset($request->subscriptionPlan) ? $request->subscriptionPlan : 'M';
            $subscriptionPlanAmount = $request->subscriptionPlanAmount * 100;
            $currentTime = time();
            
            //get plan details
            $plan = \DB::table('subscription_plans')->where('id', $planId)->first();

            $stripePlanId = '';
            $interval='year';
            if($subscriptionPlan == 'M'){
                $stripePlanId = $plan->stripe_monthly_plan_id;
                $interval='month';
            }else{
                $stripePlanId = $plan->stripe_yearly_plan_id;
                $interval='year';
            }

            $stripeCurrency = 'usd';
            if($currencyCode != ''){
                $stripeCurrency = $currencyCode;
            }

            if($currency == '&#8377;'){
                $stripeCurrency = 'inr';
            }

            $netAmount = $request->netAmmount * 100;
            
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            try{
                $customer = Stripe\Customer::create(array(
                    "address" => [
                            "line1" => '510 Townsend St',
                            "postal_code" => '98140',
                            "city" => "San Francisco",
                            "state" => "CA",
                            "country" => "US",
                        ],
                    "email" => $user->email,
                    "name" => $user->firstname.' '. $user->lastname,
                    "source" => $request->stripeToken
                ));
            }catch(Exception $e){
                return redirect()->back()->with('error_message',$e->getMessage());
            }
            

            if($stripePlanId == ''){
                //Creating a new Plan
                $savePlanName = "americansoftskill_".$currentTime;
                $savePlanId = "americansoftskill_".$currentTime;

                $stripePlan = Stripe\Plan::create(array(
                        "amount" => round($subscriptionPlanAmount),
                        "interval" => $interval,
                        "product" => array(
                            "name" => $savePlanName
                        ),
                        "currency" => $stripeCurrency,
                        "id" => $savePlanId
                    ));
                
                if(isset($stripePlan->id))
                {
                    $stripePlanId = $stripePlan->id;
                    //Update Settings
                    if($subscriptionPlan == 'M'){
                        \DB::table('subscription_plans')->where('id', $planId)->update(['stripe_monthly_plan_id'=>$stripePlanId]);
                    }else{
                        \DB::table('subscription_plans')->where('id', $planId)->update(['stripe_yearly_plan_id'=>$stripePlanId]);
                    }
                }
            }
            try{
                $payment = Stripe\Charge::create ([
                    "amount" => round($netAmount),
                    "currency" => $stripeCurrency,
                    "customer" => $customer->id,
                    "description" => "Organization payment",
                    "shipping" => [
                    "name" => $user->firstname.' '. $user->lastname,
                    "address" => [
                        "line1" => '510 Townsend St',
                        "postal_code" => '98140',
                        "city" => "San Francisco",
                        "state" => "CA",
                        "country" => "US",
                    ],
                    ]
                ]); 
            }catch(Exception $e){
                // echo $e; exit;
                return redirect()->back()->with('error_message',$e->getMessage());
            }
        
            // Save records in to database
            $subscriptionPlan = isset($request->subscriptionPlan) ? $request->subscriptionPlan : 'M';
            $subscriptionId = $payment['id'];
            $subscriptionAmount = $payment['amount'];
            $subscriptionSatrtDate = date("Y-m-d");
            $subscriptionEndDate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "+1 month" ) );
            if($subscriptionPlan == 'Y'){
                $subscriptionEndDate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "+1 year" ) );
            }

            // Check user old subscription
            $subscription = $this->SubscriptionModel->GetSubscriptionDetailsByUserId($loggedUserOrganizationId);
            if($subscription){
                $oldSubscriptionEndDate = date("Y-m-d",strtotime($subscription->subscription_end_date));
                $currentDate = date("Y-m-d");
                if($oldSubscriptionEndDate > $currentDate){
                    $subscriptionSatrtDate = $oldSubscriptionEndDate;
                    $subscriptionEndDate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $oldSubscriptionEndDate ) ) . "+1 month" ) );
                }
            }

            $subData['organization_id'] = $loggedUserOrganizationId;
            $subData['subscription_id'] = $subscriptionId;
            $subData['subscription_amount'] = $subscriptionAmount;
            $subData['subscription_start_date'] = $subscriptionSatrtDate;
            $subData['subscription_end_date'] = $subscriptionEndDate;

            // Save subscription
            $this->SubscriptionModel->SaveSubscription($subData);

            //Update subscription info
            $orgData['is_subscription'] = 1;
            $orgData['subscription_end_date'] = $subscriptionEndDate;
            $orgData['sub_plan_id'] = $planId;
            $orgData['is_first_time_subscription'] = 1;
            $orgData['sub_customer_id'] = $customer->id;
            $orgData['user_license'] = $userLicense;
            $orgData['sub_paln_type'] = $subscriptionPlan;
            
            $this->OrganizationModel->UpdateOrganizationSubscriptionSettings($orgData, $loggedUserOrganizationId);

            return redirect()->route('upgrade');
        }catch(Exception $e){
            return redirect()->back()->with('error_message',$e->getMessage());
        }
    }

    public function cancelSubscription(Request $request)
    {
        //Get logged user Id
        $loggedUserId = $request->session()->get('LoggedUserId');
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');

        //Get Organization Settings
        $settings = \DB::table('organization_settings')->where('organization_id', $loggedUserOrganizationId)->first();
        $isFirstTimeSubscription = $settings->is_first_time_subscription;

        //Get Users latest subscription
        $subscription = \DB::table('organization_subscription')->where('organization_id', $loggedUserOrganizationId)->orderBy('id', 'desc')->first();

        if($subscription){
            if($isFirstTimeSubscription == 0){
                //Load Strip Key
                Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                $subscription  = \Stripe\Subscription::retrieve($subscription->subscription_id);
                $subscription->cancel();
            }
            //Update subscription info
            $orgData['is_subscription'] = 0;
            $orgData['subscription_end_date'] = null;
            $orgData['sub_plan_id'] = 0;
            $orgData['sub_paln_type'] = null;
            $orgData['is_first_time_subscription'] = 0;
            $orgData['sub_customer_id'] = null;
            $this->OrganizationModel->UpdateOrganizationSubscriptionSettings($orgData, $loggedUserOrganizationId);
        }
        
        //Return Output
        $retMessage = 'Subscription Canceled';

        //Return Ajax Request
        if($request->ajax())
		{            
            $response = array('status'=>'success', 'message'=>$retMessage);
			return response()->json($response);
		}
    }

    public function userCheckout(Request $request)
    {
        $loggedUserId = $request->session()->get('LoggedUserId');
        $loggedUserCountryId = $request->session()->get('loggedUserCountryId');

        $categoryId = isset($request->categoryId) ? $request->categoryId : 0;
        // Category List
        $courseCategories = $this->CourseCategoryModel->GetAllCategories();

        //get Subscribed category list
        $subscribedCategories = $this->CourseCategoryModel->GetAllUserSubscribedCategories($loggedUserId);
        // Pluck Subscribed Category Ids
        $subscribedCategoryIds = $subscribedCategories->pluck('category_id')->toArray();

        //Get plan details for all category
        $planDetailsForAll = $this->CourseCategoryModel->GetSubscriptionPlanForAllByCountry($loggedUserCountryId);

        $this->data['pageTitle'] = "Checkout";	
        $this->data['courseCategories'] = $courseCategories;	
        $this->data['categoryId'] = $categoryId;	
        $this->data['subscribedCategoryIds'] = $subscribedCategoryIds;
        $this->data['loggedUserCountryId'] = $loggedUserCountryId;
        $this->data['planDetailsForAll'] = $planDetailsForAll;
        
        return view('user_checkout',$this->data);
    }

    public function userPayment(Request $request)
    {
        $loggedUserId = $request->session()->get('LoggedUserId');
        $planAmount = $request->netAmount;

        $this->data['pageTitle'] = "Payment";	
        $this->data['planAmount'] = $planAmount;

        if($request->ajax())
		{
			$response = array('status'=>'success', 'message'=>'', 'view'=>view('user_payment_modal', $this->data)->render());
			return response()->json($response);
		}
      
        return view('user_payment_modal',$this->data);
    }

    public function userPaymentPost(Request $request)
    {
        try{
            //Get logged user Id
            $categoryIds = $request->categoryIds;
            $loggedUserId = $request->session()->get('LoggedUserId');
            $user = $this->UserModel->GetUserDetails($loggedUserId);
            $currency = $request->session()->get('currency');
            $currencyCode = strtolower($request->session()->get('currencyCode'));

            $netAmount = $request->netAmmount * 100;
            $stripeCurrency = 'inr';
            if($currencyCode != ''){
                $stripeCurrency = $currencyCode;
            }
            if($currency == '$'){
                $stripeCurrency = 'usd';
            }

            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            try{
                $customer = Stripe\Customer::create(array(
                    "address" => [
                            "line1" => '510 Townsend St',
                            "postal_code" => '98140',
                            "city" => "San Francisco",
                            "state" => "CA",
                            "country" => "US",
                        ],
                    "email" => $user->email,
                    "name" => $user->firstname.' '. $user->lastname,
                    "source" => $request->stripeToken
                ));

                $payment = Stripe\Charge::create ([
                    "amount" => round($netAmount),
                    "currency" => $stripeCurrency,
                    "customer" => $customer->id,
                    "description" => "One time payment for guest user",
                    "shipping" => [
                    "name" => $user->firstname.' '. $user->lastname,
                    "address" => [
                        "line1" => '510 Townsend St',
                        "postal_code" => '98140',
                        "city" => "San Francisco",
                        "state" => "CA",
                        "country" => "US",
                    ],
                    ]
                ]);

            }catch(Exception $e){
                return redirect()->back()->with('error_message',$e->getMessage());
            }   
        
            // Save records in to database
            $subscriptionId = $payment['id'];
            $subscriptionAmount = $payment['amount'];
            $subscriptionSatrtDate = date("Y-m-d");
            $subscriptionEndDate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "+1 year" ) );

            $subData['user_id'] = $user->id;
            $subData['subscription_id'] = $subscriptionId;
            $subData['subscription_amount'] = $subscriptionAmount;
            $subData['subscription_start_date'] = $subscriptionSatrtDate;
            $subData['subscription_end_date'] = $subscriptionEndDate;

            // Save subscription
            $this->SubscriptionModel->SaveSubscription($subData);

            //Update subscription info
            $userData['is_subscription'] = 1;
            $userData['subscription_end_date'] = $subscriptionEndDate;
                        
            $this->UserModel->UpdateUser($userData, $user->id);

            // Save user subscribed categories
            $categoryIds = explode(',', $categoryIds);
            foreach($categoryIds as $categoryId){
                $categoryData['subscription_id'] = $subscriptionId;
                $categoryData['user_id'] = $user->id;
                $categoryData['category_id'] = $categoryId;

                \DB::table('user_subscribed_categories')->insert($categoryData);
            }

            return redirect()->route('userdashboard');
        }catch(Exception $e){
            return redirect()->back()->with('error_message',$e->getMessage());
        }
    }
}
