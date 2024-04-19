<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\User;
use Session;

/** 
   * @Discription CouponsController
   * @Author     Ashvinee Chavan <ashwini@vizipp.com>
   * @Date       12-04-2023
   */

class CouponsController extends Controller
{
    private $UserModel;
    private $CouponModel;
    private $module_name;

    public function __construct()
    {
        $this->UserModel = new User();
        $this->CouponModel = new Coupon();
        $this->module_name = "coupons";
    }

    public function index()
    {
        $coupons = $this->CouponModel->GetAllCoupons();
        return view('admin.coupon.index')->withUsers($users);
    }
    
    public function coupons(Request $request)
    {
        return view('admin.coupon.index');
    }

    /*AJAX request*/
    public function list(Request $request)
    {
        //Get logged user details
        $loggedUserId = $request->session()->get('LoggedUserId');

        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        //Total records
        $totalRecords = $this->CouponModel->GetTotalCouponsCount();
        $totalRecordswithFilter = $this->CouponModel->GetCouponByFilter($searchValue);

        //Fetch records
        $records = $this->CouponModel->GetCouponForDT($columnName, $columnSortOrder,$searchValue,$start, $rowperpage);

        $data_arr = array();
        
        foreach($records as $record){
          
            $couponCode = $record->coupon_code;
            $discounttype = '';
            if($record->discount_type == '1'){
                $discounttype = 'Free';
            }else if($record->discount_type == '2'){
                $discounttype = '%';
            }else if($record->discount_type == '3'){
                $discounttype = 'USD OFF';
            }
            
            $startDate = date("m/d/Y",strtotime($record->start_date));
            $endDate = date("m/d/Y",strtotime($record->end_date));

            $editUrl = "/coupons/create/".$record->id;
            $deleteUrl = "/coupons/delete/".$record->id;
            $action = '<a href="javascript:void(0);" onclick="openCreateCouponModal(\''.$editUrl.'\')" class="btn btn-sm btn-icon item-edit"><i class="bx bxs-edit"></i></a>';
            $action .= '<a href="javascript:void(0);" onclick="deleteCoupon(this, \''.$deleteUrl.'\')" class="btn btn-sm btn-icon item-edit"><i class="bx bx-trash"></i></a>';

            $couponCode = '<div class="d-flex justify-content-start align-items-center user-name">
                        <div class="d-flex flex-column">
                            <a href="" class="text-body text-truncate"><span class="fw-semibold">'.$couponCode.'</span></a>
                        </div>
                    </div>';

            $data_arr[] = array(
                "id" => $record->id,
                "code" => $couponCode,
                "type" => $discounttype,
                "sdate" => $startDate,
                "edate" => $endDate,
                "action" => $action
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }

    public function create(Request $request)
    {
        //Get logged user Id
        $loggedUserId = $request->session()->get('LoggedUserId');

        $input = $request->all();
        $couponId = isset($request->id) ? $request->id : 0;

        // Create 6 digit Coupon Code
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $couponCode = substr(str_shuffle($str_result), 0, 6);
        
        $pageTitle = 'Add Coupon';     
        $discountType = "";       
        $discount = "";       
        $noOfRedemption = "";  
        $startDate = "";  
        $endDate = "";  
      
        if($couponId>0)
        {
            $pageTitle = 'Update Coupon';
            $coupon = $this->CouponModel->GetCouponDetails($couponId);
            if (is_null($coupon)) {
                return redirect()->back()->with('error_message', 'Coupon does not exist.');
            }
            $couponCode = $coupon->coupon_code;
            $discountType = $coupon->discount_type;
            $discount = $coupon->discount;       
            $noOfRedemption = $coupon->no_of_redemption;   
            $startDate = date("Y-m-d",strtotime($coupon->start_date));
            $endDate = date("Y-m-d",strtotime($coupon->end_date));
            	
        }
        $this->data['pageTitle'] = $pageTitle;	
        $this->data['couponCode'] = $couponCode;		
        $this->data['discountType'] = $discountType;		
        $this->data['discount'] = $discount;		
        $this->data['noOfRedemption'] = $noOfRedemption;		
        $this->data['startDate'] = $startDate;		
        $this->data['endDate'] = $endDate;   
        $this->data['couponId'] = $couponId;   
        
        if($request->ajax())
		{
			$response = array('status'=>'success', 'message'=>'', 'view'=>view('admin.coupon.create', $this->data)->render());
			return response()->json($response);
		}
      
        return view('admin.coupon.create',$this->data);
    }    

    public function save(Request $request){
        
        //Get logged user Id
        $loggedUserId = $request->session()->get('LoggedUserId');

        //Get Input
        $input = $request->all();
        $couponId = isset($request->id) ? $request->id : 0;

        //Save Record     
        if($couponId==0)  {
            $this->CouponModel->SaveCoupon($input);     
        }
        else
        {
            $coupon = $this->CouponModel->GetCouponDetails($couponId);
            if (is_null($coupon)) {
                $response = array('status'=>'error', 'message'=>'Coupon does not exist.');
                return response()->json($response);
            }

            $this->CouponModel->UpdateCoupon($input, $couponId);     
        }

        //Return Message
        $retMessage = $couponId ? 'Updated' : 'Added';

        //Return Ajax Request
        if($request->ajax())
		{            
            $response = array('status'=>'success', 'message'=>$retMessage);
			return response()->json($response);
		}         
       
        //Return Output
        return redirect()->route('coupons')->withSuccess($retMessage);
    }

    public function delete(Request $request)
    {
		try {
   
            //Get logged user Id
            $loggedUserId = $request->session()->get('LoggedUserId');
			
            //Get Input
            $couponId = isset($request->id) ? $request->id : 0;

            //Find coupon
            $coupon = $this->CouponModel->GetCouponDetails($couponId);
            if (is_null($coupon)) {
                $response = array('status'=>'error', 'message'=>'User does not exist.');
                return response()->json($response);
            }       
            
            //Delete
            $this->CouponModel->DeleteCoupon($couponId);

        } catch (Exception $e) {
			if($request->ajax())
			{
				$response = array('status'=>'error', 'message'=>$e->getMessage());
				return response()->json($response);
			}
        }		
		
		$response = array('status'=>'success', 'message'=>'Deleted.');
		return response()->json($response);
    }

    public function couponDetails(Request $request)
    {
        //Get logged user Id
        $loggedUserId = $request->session()->get('LoggedUserId');
        $currency = $request->session()->get('currency');
        
        $input = $request->all();
        $couponCode = isset($request->code) ? $request->code : 0;
        $errorMassage = '';
        $discountType = "";       
        $discount = "";       

        $coupon = $this->CouponModel->GetCouponDetailsByCode($couponCode);

        $date_now = date("Y-m-d"); // this format is string comparable
        if (is_null($coupon)) {
            $errorMassage = 'Invalid coupon code';
        }else{
            if ($date_now < $coupon->start_date) {
                $errorMassage = 'Can not use this coupon early';
            }else{
                if($date_now >= $coupon->end_date){
                    $errorMassage = 'This coupon has been expired';
                }
            }
            $discountType = $coupon->discount_type;       
            $discount = 0; //$coupon->discount;     
            if($coupon->discount_type == 1){
                $discount = 'Free';
            }else if($coupon->discount_type == 2){
                $discount = $coupon->discount.'%';
            }else{
                if($currency == '&#8377;'){
                    $discount = $coupon->discount * 80;
                    // $discount = '&#8377;'.$discount;
                }else{
                    // $discount = '$'.$coupon->discount;
                    $discount = $coupon->discount;
                }
                
            }
        }
        
        $this->data['couponCode'] = $couponCode;		
        $this->data['discountType'] = $discountType;		
        $this->data['discount'] = $discount;
        $this->data['errorMassage'] = $errorMassage;  
        $this->data['currency'] = $currency;  
                 
        $response = array('status'=>'success', 'couponData'=>$this->data);
		return response()->json($response);
    }
}
