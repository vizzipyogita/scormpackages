<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Validator;

/** 
   * @Discription Subscription Model
   * @Author     Ashvinee Chavan <ashwini@vizipp.com>
   * @Date       07-06-2023
   */

class Subscription extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps = true;    
    protected $table = 'organization_subscription';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organization_id',
        'subscription_id',
        'subscription_amount',
        'subscription_start_date',
        'subscription_end_date'
    ];

    public static function SaveSubscription($input)
    {
        $subscription = new Subscription;

        //Save Coupon Info
        $subscription->organization_id = isset($input['organization_id']) ? $input['organization_id'] : 0;
        $subscription->user_id = isset($input['user_id']) ? $input['user_id'] : 0;
        $subscription->subscription_id= $input['subscription_id'];
        $subscription->subscription_amount= $input['subscription_amount'];
        $subscription->subscription_start_date = date("Y-m-d",strtotime($input['subscription_start_date']));
        $subscription->subscription_end_date = date("Y-m-d",strtotime($input['subscription_end_date']));      

        $subscription->save();

        return $subscription;
    }

    public static function GetSubscriptionDetailsByUserId($organizationId){        
        $subscription = Subscription::where('organization_id', $organizationId)->orderBy('id', 'desc')->first();
        return $subscription;
    }
}
