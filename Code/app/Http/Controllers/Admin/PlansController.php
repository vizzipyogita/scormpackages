<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlans;
use App\Models\Country;
use App\Models\CourseCategory;
use Session;

class PlansController extends Controller
{
    var $data = array();
    private $SubscriptionPlansModel;
    private $CountryModel;
    private $CourseCategoryModel;

    public function __construct()
    {
        $this->SubscriptionPlansModel = new SubscriptionPlans();
        $this->CountryModel = new Country();
        $this->CourseCategoryModel = new CourseCategory();
    }

    public function plans(Request $request)
    {
        // Get Country plan details
        $countryPlans = $this->SubscriptionPlansModel->GetAllCountriesPlansForDT();

        $countries = $this->CountryModel->GetAllCounries();

        $this->data['countryPlans'] = $countryPlans;
        $this->data['countries'] = $countries;

        return view('admin.payment.plans', $this->data);
    }

    public function create(Request $request)
    {
        $pageTitle = 'Add Plan';  
        $isUserPlan = isset($request->isUserPlan) ? $request->isUserPlan : 0;
        $categories =[];
        
         // Get Country plan details
         $countryPlans = $this->SubscriptionPlansModel->GetAllCountriesPlans($isUserPlan);
         $countryIds = $countryPlans->pluck('country_id')->toArray();  

         $countries = $this->CountryModel->GetAllCounries();

        if($isUserPlan == 1){
            // Category List
            $categories = $this->CourseCategoryModel->GetAllCategories(9);
        }
              
        $this->data['pageTitle'] = $pageTitle;	
        $this->data['countries'] = $countries;	
        $this->data['countryIds'] = $countryIds;	
        $this->data['categories'] = $categories;
        $this->data['isUserPlan'] = $isUserPlan;
        
        if($request->ajax())
		{
			$response = array('status'=>'success', 'message'=>'', 'view'=>view('admin.payment.create_plan', $this->data)->render());
			return response()->json($response);
		}
      
        return view('admin.payment.create_plan',$this->data);
    }    

    public function save(Request $request){
        
        //Get Input
        $input = $request->all();
        $planId = isset($request->id) ? $request->id : 0;
        $isUserPlan = isset($request->isUserPlan) ? $request->isUserPlan : 0;

        if($planId == 0){
            $planName = $request->plan_name;
            $categoryId = isset($request->category_id) ? $request->category_id : [];
            $monthlyAmount = isset($request->monthly_plan_amount) ? $request->monthly_plan_amount : [];
            $yearlyAmount = $request->yearly_plan_amount;   
            $isAllCategory = isset($request->is_all_category) ? $request->is_all_category : [];
            $planData = [];
            if(count($planName)){
                for($i=0; $i < count($planName); $i++){
                    if($isUserPlan == 0){
                        $planData['country_id'] = $request->country_id;
                        $planData['plan_name'] = $planName[$i];
                        $planData['monthly_plan_amount'] = $monthlyAmount[$i];
                        $planData['yearly_plan_amount'] = $yearlyAmount[$i];
                    }else{
                        $planData['country_id'] = $request->country_id;
                        $planData['category_id'] = $categoryId[$i];
                        $planData['plan_name'] = $planName[$i];
                        $planData['yearly_plan_amount'] = $yearlyAmount[$i];
                        $planData['is_all_category'] = $isAllCategory[$i];
                    }
                    
                    $this->SubscriptionPlansModel->SavePlan($planData); 
                }
            }
        }else{
            $plan = $this->SubscriptionPlansModel->GetPlanDetails($planId);
            if (is_null($plan)) {
                $response = array('status'=>'error', 'message'=>'Plan does not exist.');
                return response()->json($response);
            }

            $this->SubscriptionPlansModel->UpdatePlan($input, $planId);  
        }

        //Return Message
        $retMessage = $planId ? 'Updated' : 'Added';

        //Return Ajax Request
        if($request->ajax())
		{            
            $response = array('status'=>'success', 'message'=>$retMessage);
			return response()->json($response);
		}         
       
        //Return Output
        return redirect()->route('plans')->withSuccess($retMessage);
    }

    public function view(Request $request)
    {
        $countryId = isset($request->country_id) ? $request->country_id : 0;
        $isUserPlan = isset($request->isUserPlan) ? $request->isUserPlan : 0;

        //Get country plans
        $plans = $this->SubscriptionPlansModel->GetSubscriptionPlansByCountryId($countryId, $isUserPlan);
        // echo "<pre>";print_r($plans);exit;
        //Get Country Details
        $country = $this->CountryModel->GetCountryDetails($countryId);
        $pageTitle = 'View Plan for '. $country->title; 

        $this->data['pageTitle'] = $pageTitle;		
        $this->data['plans'] = $plans;	
        $this->data['isUserPlan'] = $isUserPlan;
        $this->data['countryId'] = $countryId;	
        
        if($request->ajax())
		{
			$response = array('status'=>'success', 'message'=>'', 'view'=>view('admin.payment.view_plan', $this->data)->render());
			return response()->json($response);
		}
      
        return view('admin.payment.view_plan',$this->data);
    }   

}
