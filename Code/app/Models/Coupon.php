<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Validator;

/** 
   * @Discription Coupon Model
   * @Author     Ashvinee Chavan <ashwini@vizipp.com>
   * @Date       12-04-2023
   */

class Coupon extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps = true;    
    protected $table = 'coupons';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'coupon_code',
        'discount_type',
        'discount',
        'no_of_redemption',
        'start_date',
        'end_date'
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function SaveCoupon($input)
    {
        $coupon = new Coupon;

        //Save Coupon Info
        $coupon->coupon_code = $input['coupon_code'];
        $coupon->discount_type= $input['discount_type'];
        $coupon->discount= $input['discount'];
        $coupon->no_of_redemption = $input['no_of_redemption'];
        $coupon->start_date = date("Y-m-d",strtotime($input['start_date']));
        $coupon->end_date = date("Y-m-d",strtotime($input['end_date']));      

        $coupon->save();

        return $coupon;
    }

    public static function UpdateCoupon($input, $id)
    {
        //Find coupon
        $coupon = Coupon::find($id);

        if(isset($input['coupon_code']) && $input['coupon_code']!="")
            $coupon->coupon_code = $input['coupon_code'];

        if(isset($input['discount_type']) && $input['discount_type']!="")
            $coupon->discount_type = $input['discount_type'];

        if(isset($input['discount']) && $input['discount']!="")
            $coupon->discount = $input['discount'];  

        if(isset($input['no_of_redemption']) && $input['no_of_redemption']!="")
            $coupon->no_of_redemption = $input['no_of_redemption'];

        if(isset($input['start_date']) && $input['start_date']!="")
            $coupon->start_date = date("Y-m-d",strtotime($input['start_date']));
        
        if(isset($input['end_date']) && $input['end_date']!="")
            $coupon->end_date = date("Y-m-d",strtotime($input['end_date'])); 

        //Save coupon Info
        $coupon->save();

        return $coupon;
    }

    public static function GetCouponDetails($id){        
        $coupon = Coupon::find($id);
        return $coupon;
    }

    public static function GetAllCoupos(){        
        $coupons = Coupon::orderby('created_at', 'DESC')->get();
        return $coupons;
    }

    public static function GetTotalCouponsCount(){        
        $count = Coupon::where('coupon_code', '<>', '')->count();
        return $count;
    }

    public static function GetCouponByFilter($search_value){        
        $count = Coupon::where('coupon_code', 'like', '%' .$search_value . '%')->count();
        return $count;
    }

    public static function GetCouponForDT($column_name, $column_sortorder,$search_value, $start, $row_perpage)
    {        
        $records = Coupon::orderBy($column_name,$column_sortorder)
                                ->where('coupons.coupon_code', 'like', '%' .$search_value . '%')
                                ->select('coupons.*')
                                ->skip($start)
                                ->take($row_perpage)
                                ->get();
        return $records;
    }

    public static function DeleteCoupon($id){        
        Coupon::where('id', $id)->firstorfail()->delete();
        return true;
    }

    public static function GetCouponDetailsByCode($code){        
        $coupon = Coupon::where('coupon_code', $code)->first();
        return $coupon;
    }
}
