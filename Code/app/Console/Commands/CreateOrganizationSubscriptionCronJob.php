<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use DB;
use Stripe;

class CreateOrganizationSubscriptionCronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'organization:createorganizationsubscription';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check organization subscription status';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }  
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currentDate = date("Y-m-d");
        $organizations = DB::table('organization_settings')->where('is_subscription', 1)->where('subscription_end_date', $currentDate)->get();

        if(count($organizations))
        {
            foreach($organizations as $organization)
            {  
                $subData = [];
                //Get Users latest subscription
                $subscription = DB::table('organization_subscription')->where('organization_id', $organization->organization_id)->orderBy('id', 'desc')->first();
                Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

                if($organization->is_first_time_subscription == 1){
                    //get stripe plan id
                    $plan = DB::table('subscription_plans')->where('id', $organization->sub_plan_id)->first();
                    $stripePlanId='';
                    if($organization->sub_paln_type == 'M'){
                        $stripePlanId=$plan->stripe_monthly_plan_id;
                    }else{
                        $stripePlanId=$plan->stripe_yearly_plan_id;
                    }
                    if($stripePlanId !=''){
                        //creating a subscription
                        $subscription = \Stripe\Subscription::create(array(
                            "customer" => $organization->sub_customer_id,
                            "items" => array(
                            array(
                                "plan" => $stripePlanId,
                            ),
                            )
                        ));
                    }
                }else{
                    $subscription = \Stripe\Subscription::retrieve($subscription->subscription_id);
                } 

                // get Subscription plan data
                $planData = $subscription['plan'];
                $interval = $planData['interval'];
                $subscriptionAmount = $planData['amount'];
                $subscriptionId = $subscription->id;
                $subscriptionSatrtDate = date('Y/m/d', $subscription['current_period_start']);
                $subscriptionEndDate = date('Y/m/d', $subscription['current_period_end']);

                // Update Subscription Data
                $subData['organization_id'] = $organization->organization_id;
                $subData['subscription_id'] = $subscriptionId;
                $subData['subscription_amount'] = $subscriptionAmount;
                $subData['subscription_start_date'] = $subscriptionSatrtDate;
                $subData['subscription_end_date'] = $subscriptionEndDate;

                DB::table('organization_subscription')->insert($subData);

                //Update Organization Subscription info
                $orgData['is_subscription'] = 1;
                $orgData['subscription_end_date'] = $subscriptionEndDate;
                $orgData['is_first_time_subscription'] = 0;

                DB::table('organization_settings')->where('organization_id', $organization->organization_id)->update($orgData);
            }
        }        
         
        $this->info('Check Subscription');
    }
}