<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Str;
use Validator;
use Mail;
use App\Mail\ForgotPasswordEmail;
use App\Mail\RegistrationEmail;
use App\Rules\ReCaptcha;

class UserLoginController extends Controller
{
    private $UserModel;

    public function __construct()
    {
        $this->UserModel = new User();
    }

    public function index(Request $request)
    {
        return redirect()->route('userlogin');
    }

    public function userlogin(Request $request)
    {
        return view('user_login');
        if($request->session()->has('IsUserLoggedIn')){
            return redirect()->route('dashboard');
        }else{
            return view('user_login');
        }
    }

    public function userforgotpassword(Request $request)
    {
        return view('user_forgotpassword');
    }

    public function userforgotpasswordpost(Request $request)
    {  	
        $data = $request->all();	 
		$user = User::where('email', $request->email)->first();  
		if($user)
		{
			//Find is Inactive
			if($user->is_active==0)
				return redirect("userforgetpassword")->withError('Your account is deactivated. Please contact administrator.');
		
			//Send email
			$data = ['ToEmailAddr'=>$user->email, 'ToUserName'=>$user->firstname, 'password' =>$user->normal_password];
			try {
				Mail::to($user->email)->send(new ForgotPasswordEmail($data));
			} catch (\Exception $e) {
                // print_r($e);exit;
				return $e->getMessage();
			}
            return redirect("/user/login")->with('success_message','Password reset successfully, Please check your mail.');
		}   
		else{
            return redirect()->back()->with('error_message','Please enter your registered email address.');
		} 

		
    }

    public function userloginpost(Request $request)
    {
        if ($request->isMethod('get')) {
            $email = $request->email;
            // echo $email;exit;
            //get user by email
            $user = User::where('email', $email)->first();  
            // print_r($user);exit;
            if (is_null($user)) {
                return redirect("/user/login")->with('error_message', 'User does not exist.');
            }
            if(Auth::guard('user')->attempt(['email' => $user->email, 'password' => $user->normal_password])){ 
    
                $user = Auth::guard('user')->user(); //Auth::user(); 
    
                //Set Session
                User::SetSession($user);
                $request->session()->put('isUserLogin', 1);
                $request->session()->put('isLoginFromIsmailiChamber', 1);
    
                return redirect()->route('userdashboard');                
            } 
        }else{
            $validator = Validator::make($request->all(), [           
                'email' => 'required|email',
                'password' => 'required',
              //  'g-recaptcha-response' => ['required', new ReCaptcha]
            ]);
       
            if($validator->fails()){
                return redirect()->back()->with('error_message', $validator->errors());    
            }
    
            if(Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password])){ 
    
                $user = Auth::guard('user')->user(); //Auth::user(); 
    
                //Set Session
                User::SetSession($user);
                $request->session()->put('isUserLogin', 1);
    
                return redirect()->route('userdashboard');
                // if($user->user_type !='A'){
                //     return redirect()->route('userdashboard');
                // }else{
                //     return redirect()->back()->with('error_message','Unauthorized User');
                // }
                
            } 
            else{ 
                return redirect()->back()->with('error_message','Invalid email or password.');
            }     
        }
    }

    public function userlogout(Request $request)
    {
        Auth::guard('user')->logout();        
        $request->session()->flush();
        return redirect()->route('userlogin')->with('success_message','Logout successfully');
    }

    public function userSignup(Request $request)
    {
        return view('user_signup');
    }

    public function userSignuppost(Request $request)
    {
        $validator = Validator::make($request->all(), [           
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => ['required', new ReCaptcha]
        ]);
        
        if($validator->fails()){
            return redirect()->back()->with('error_message', "The email has already been taken. Please try with new email");    
        }
        
        //Get Input
        $input = $request->all();
        $organization_id = 2; // Do not change this id because it is for oonly vizipp academy organization
        $input['is_guest'] = 1;// Do not change this id because it is for oonly vizipp academy organization
        //Create User
        $systeamUser = $this->UserModel->SaveUser($input, 'T', $organization_id);    

        //Send User Registered email
        $data = ['ToEmailAddr'=>$systeamUser->email, 'ToUserName'=>$systeamUser->firstname, 'password' =>$systeamUser->normal_password, 'isGuestUser'=>$systeamUser->is_guest];
        try {
            Mail::to($systeamUser->email)->send(new RegistrationEmail($data));
        } catch (\Exception $e) {
            // print_r($e);exit;
            return $e->getMessage();
        }

        //Return Output
        $retMessage = 'You are successfully registered.';
        return redirect()->route('userlogin')->with('success_message', $retMessage);
    }
    
}
