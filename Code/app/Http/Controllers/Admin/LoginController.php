<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Organization;
use App\Models\RolePermission;
use App\Models\Role;
use Hash;
use Str;
use Validator;
use Mail;
use App\Mail\ForgotPasswordEmail;
use App\Mail\RegistrationEmail;
use App\Rules\ReCaptcha;

class LoginController extends Controller
{
    private $OrganizationModel;

    public function __construct()
    {
        $this->OrganizationModel = new Organization();
    }

    public function index(Request $request)
    {
        // $ip = getVisIpAddr();
        // $ipdat = @json_decode(file_get_contents(
        //     "http://www.geoplugin.net/json.gp?ip=" . $ip));

        // echo "<pre>";print_r($ipdat);exit;
           
        // echo 'Country Name: ' . $ipdat->geoplugin_countryName . "\n";
        // echo 'City Name: ' . $ipdat->geoplugin_city . "\n";
        // echo 'Continent Name: ' . $ipdat->geoplugin_continentName . "\n";
        // echo 'Latitude: ' . $ipdat->geoplugin_latitude . "\n";
        // echo 'Longitude: ' . $ipdat->geoplugin_longitude . "\n";
        // echo 'Currency Symbol: ' . $ipdat->geoplugin_currencySymbol . "\n";
        // echo 'Currency Code: ' . $ipdat->geoplugin_currencyCode . "\n";
        // echo 'Timezone: ' . $ipdat->geoplugin_timezone;
        // exit;
        return view('welcome');
        // return redirect()->route('login');
    }

    public function login(Request $request)
    {
        //Use this in registration
        //$token = Str::random(60);
        //$api_token = hash('sha256', $token);
        //echo $api_token;exit;
        return view('login');
        if($request->session()->has('IsUserLoggedIn')){
            return redirect()->route('dashboard');
        }else{
            return view('login');
        }
    }

    public function forgotpassword(Request $request)
    {
        return view('forgotpassword');
    }

    public function forgotpasswordpost(Request $request)
    {  	
        $data = $request->all();	 
		$user = User::where('email', $request->email)->first();  
		if($user)
		{
			//Find is Inactive
			if($user->is_active==0)
                return redirect()->route('forgetpassword')->with('error_message','Your account is deactivated. Please contact administrator.');
		
			//Send email
			$data = ['ToEmailAddr'=>$user->email, 'ToUserName'=>$user->firstname, 'password' =>$user->normal_password];
			try {
				Mail::to($user->email)->send(new ForgotPasswordEmail($data));
			} catch (\Exception $e) {
                print_r($e);exit;
				return $e->getMessage();
			}
            return redirect()->route('forgotpassword')->with('success_message','Password reset successfully, Please check your mail.');
		}   
		else{
            return redirect()->route('forgetpassword')->with('error_message','Please enter your registered email address.');
		} 

		
    }

    public function loginpost(Request $request)
    {
        $validator = Validator::make($request->all(), [           
            'email' => 'required|email',
            'password' => 'required',
           // 'g-recaptcha-response' => ['required', new ReCaptcha]
        ]);
   
        if($validator->fails()){
            return redirect()->back()->with('error_message', $validator->errors());    
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 

            $user = Auth::user(); 

            //Set Session
            User::SetSession($user);

            return redirect()->route('dashboard');
        } 
        else{ 
            return redirect()->back()->with('error_message','Invalid email or password.');
        }        
       
    }

    public function logout(Request $request)
    {
        Auth::logout();        
        $request->session()->flush();
        return redirect()->route('login')->with('success_message','Logout successfully');
    }

    public function loginasorganization(Request $request)
    {
        //Get Logged User details
        $previousLoggedUserId = $request->session()->get('LoggedUserId');
        // echo $previousLoggedUserId; exit;
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');

        //Logout User
        Auth::logout();        
        $request->session()->flush();

        //Get organizationId
        $organizationId = $request->organizationId;
        
        //Find User
        $primarySystemUser = User::FindOrgPrimarySystemUser($organizationId);

        if(Auth::attempt(['email' => $primarySystemUser->email, 'password' => $primarySystemUser->normal_password])){ 

            $user = Auth::user(); 

            //Set Session
            User::SetSession($user, $previousLoggedUserId);

            return redirect()->route('dashboard');
        } 
        else{ 
            return redirect()->back()->with('error_message','Invalid email or password.');
        }        
       
    }

    public function signup(Request $request)
    {
        return view('signup');
    }

    public function signuppost(Request $request)
    {
        $validator = Validator::make($request->all(), [           
            'email' => 'required|email',
            'password' => 'required',
           // 'g-recaptcha-response' => ['required', new ReCaptcha]
        ]);
        
        if($validator->fails()){
            return redirect()->back()->with('error_message', "The email has already been taken. Please try with new email");    
        }
        
        //Get Input
        $input = $request->all();
        $input['contact_person_name'] = $input['firstname'].' '.$input['lastname'];
        // Generate organization code
        $organizationCode =  $input['organization_name'];
        $organizationCode = str_replace(' ', '', $organizationCode);
        $organizationCode = strtoupper($organizationCode);
        $organizationCode = substr($organizationCode, 0, 5);
        $input['organization_code'] = $organizationCode;

        //Validate Input
        $validator = $this->OrganizationModel->ValidateStoreOrganization($input);
        if($validator->fails()){
            return redirect()->back()->with('error_message', "The email has already been taken. Please try with new email")->withInput();
        }
        
        //Save Record       
        $organization = $this->OrganizationModel->SaveOrganization($input); 

        //Save Organization Settings
        $settingsInput['user_license'] = 1;
        $OrganizationSettings = $this->OrganizationModel->SaveOrganizationSettings($settingsInput, $organization->id); 

        //Create System User
        $systeamUser = User::SaveSystemUserWhileCreatingORG($organization->id, $organization->contact_person_name, $organization->email, $input['password']);

        //Send User Registered email
        $data = ['ToEmailAddr'=>$systeamUser->email, 'ToUserName'=>$systeamUser->firstname, 'password' =>$systeamUser->normal_password, 'isGuestUser'=>0];
        try {
            Mail::to($systeamUser->email)->send(new RegistrationEmail($data));
        } catch (\Exception $e) {
            // print_r($e);exit;
            return $e->getMessage();
        }

        //Create Role
        $roleInput =[];
        $roleInput['organization_id'] = $organization->id;
        $role = Role::SaveRole($roleInput, 1);

        //Set Permissions
        RolePermission::SetAdministratorRolePermission($role->id);

        //Return Output
        $retMessage = 'You are successfully registered.';
        return redirect()->route('login')->with('success_message', $retMessage);
    }
}
