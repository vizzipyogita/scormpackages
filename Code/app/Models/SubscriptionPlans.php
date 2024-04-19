<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;
use File;
use App\Models\Country;

class SubscriptionPlans extends Model
{
    use HasFactory;
    public $timestamps = true;    
    protected $table = 'subscription_plans';
    protected $fillable = [
        'plan_name',
        'country_id', 
        'category_id',
        'monthly_plan_amount',
        'yearly_plan_amount',
        'stripe_monthly_plan_id',
        'stripe_yearly_plan_id',
        'is_all_category',
    ];  
    
    public function Country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
  
    public static function GetAllSubscriptionPlans(){        
        $plans = SubscriptionPlans::get();
                    
        return $plans;
    }

    public static function GetPlanDetails($id){        
        $plan = SubscriptionPlans::find($id);
        return $plan;
    }

    public static function GetAllCountriesPlansForDT($isCategory=0){
        $countries = SubscriptionPlans::groupBy('country_id')->get();

        return $countries;
    }

    public static function GetAllCountriesPlans($isCategory=0){
        $countries = SubscriptionPlans::groupBy('country_id');
                    if($isCategory ==1){
                        $countries = $countries->where('category_id', '>', 0)->orWhere('is_all_category', 1);
                    }else{
                        $countries = $countries->where('category_id', '=', 0)->where('is_all_category', 0);
                    }
        $countries = $countries->get();

        return $countries;
    }

    public static function SavePlan($input){
        $plan = new SubscriptionPlans;

        $plan->country_id = $input['country_id'];
        $plan->plan_name = $input['plan_name'];
        $plan->monthly_plan_amount = isset($input['monthly_plan_amount']) ? $input['monthly_plan_amount'] : 0;
        $plan->yearly_plan_amount  = $input['yearly_plan_amount'];
        $plan->category_id = isset($input['category_id']) ? $input['category_id'] : 0;
        $plan->is_all_category = isset($input['is_all_category']) ? $input['is_all_category'] : 0;

        $plan->save();

        return $plan;
    }

    public static function UpdatePlan($input, $id=0)
    {
        //Find plan
        $plan = SubscriptionPlans::find($id);

        if(isset($input['monthly_plan_amount']) && $input['monthly_plan_amount']!="")
            $plan->monthly_plan_amount = $input['monthly_plan_amount'];

        if(isset($input['yearly_plan_amount']) && $input['yearly_plan_amount']!="")
            $plan->yearly_plan_amount = $input['yearly_plan_amount'];
        
        //Save plan Info
        $plan->save();

        return $plan;
    }

    public static function GetSubscriptionPlansByCountryId($countryId, $isCategory=0){        
        $plans = SubscriptionPlans::where('country_id', $countryId);
                if($isCategory ==1){
                    $plans = $plans->where('category_id', '>', 0)->orWhere('is_all_category', 1);
                }else{
                    $plans = $plans->where('category_id', '=', 0)->where('is_all_category', 0);
                }
        $plans = $plans->get();

        return $plans;
    }
}