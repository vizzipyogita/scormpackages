<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Organization;
use App\Models\Role;
use App\Models\Country;
use Session;
use Mail;
use DB;
use App\Mail\sendlogindetailsEmail;

class UsersController extends Controller
{
    private $UserModel;
    private $OrganizationModel;
    private $CountryModel;
    private $module_name;

    public function __construct()
    {
        $this->UserModel = new User();
        $this->OrganizationModel = new Organization();
        $this->CountryModel = new Country();
        $this->module_name = "systemusers";
    }

    public function index()
    {
        $users = $this->UserModel->GetAllUsers();
        return view('admin.users.index')->withUsers($users);
    }
    public function ChangeStatus(Request $request){
        
        $is_active = $request->Active;
        $UserId = $request->UserId;
        $data = ["is_active"=>$is_active];
        $users = $this->userRepository->update($UserId,$data);
        Session::flash('success','User Status Updated successfully!');
        return redirect('/users');
    }

    public function users(Request $request)
    {
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');
        $organizationId = isset($request->organizationId) ? $request->organizationId : $loggedUserOrganizationId;

        $isAddButtonDisable = 0;
        //Check license for organization
        $userCount = $this->UserModel->GetTotalOrgUsersCount($organizationId);
        $settings = $this->OrganizationModel->GetOrganizationSettings($organizationId);
        $userLicense = isset($settings) ? $settings->user_license : 1;
        if($userCount >= $userLicense){
            $isAddButtonDisable = 1;
        }

        $organizations = $this->OrganizationModel->GetAllOrganizationsWithSuperSchool();
        
        $this->data['organizations'] = $organizations;
        $this->data['loggedUserOrganizationId'] = $loggedUserOrganizationId;
        $this->data['organizationId'] = $organizationId;
        $this->data['isAddButtonDisable'] = $isAddButtonDisable;

        return view('admin.user.index',$this->data);
    }

    /*AJAX request*/
    public function list(Request $request)
    {
        //Get logged user details
        $loggedUserId = $request->session()->get('LoggedUserId');
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');

        $organizationId = isset($request->organizationId) ? $request->organizationId : $loggedUserOrganizationId;
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
        $totalRecords = $this->UserModel->GetTotalOrgSystemUsersCount($organizationId);
        $totalRecordswithFilter = $this->UserModel->GetOrgCountSystemUsersByFilter($searchValue, $organizationId);

        //Fetch records
        $records = $this->UserModel->GetOrgSystemUsersForDT($organizationId, $columnName, $columnSortOrder,$searchValue,$start, $rowperpage);

        $data_arr = array();
        
        foreach($records as $record){
          
            $systemUserName = $record->firstname." ".$record->lastname;
            $filepath = $this->UserModel->GetUserImage($record->id, $record->image_name);

            $editUrl = "/users/create/".$record->id;
            $deleteUrl = "/users/delete/".$record->id;
            $changePasswordUrl = "/users/changepassword/".$record->id;
            $sendLoginDetailsUrl = "/users/sendlogindetailsemail/".$record->id;

            $action = '<a href="javascript:void(0);" onclick="sendUserLoginDetails(\''.$sendLoginDetailsUrl.'\')" class="btn btn-sm btn-icon item-key" title="Send Login Details"><i class="bx bx-envelope"></i></a>';
            $action .= '<a href="javascript:void(0);" onclick="openChangePasswordModal(\''.$changePasswordUrl.'\')" class="btn btn-sm btn-icon item-key" title="Change Password"><i class="bx bxs-key"></i></a>';
            $action .= '<a href="javascript:void(0);" onclick="openCreateSystemUserModal(\''.$editUrl.'\')" class="btn btn-sm btn-icon item-edit" title="Edit"><i class="bx bxs-edit"></i></a>';
            $action .= '<a href="javascript:void(0);" onclick="deleteSystemUser(this, \''.$deleteUrl.'\')" class="btn btn-sm btn-icon item-edit" title="Delete"><i class="bx bx-trash"></i></a>';

            $name = '<div class="d-flex justify-content-start align-items-center user-name">
                        <div class="d-flex flex-column">
                            <label class="margin_bottom_zero">
                                <input type="checkbox" name="users[]" value="'.$record->id.'" class="" onclick="selectUserForSendLogin(this)" data-parsley-mincheck="1">
                            </label>
                        </div>
                        <div class="avatar-wrapper">
                        <div class="avatar avatar-sm me-3"><img src="'.$filepath.'" alt="Avatar" class="rounded-circle"></div></div>
                            <div class="d-flex flex-column">
                                <a href="" class="text-body text-truncate"><span class="fw-semibold">'.$systemUserName.'</span></a>
                                <small class="text-muted">'.$record->email.'</small>
                            </div>
                        </div>';
            
                        
            $role = $record->user_type=='T' ? 'User' : 'Admin';

            $status =  ($record->is_active==1) ? '<span class="badge bg-label-success">Active</span>' : '<span class="badge bg-label-warning">Inactive</span>';
          
            $data_arr[] = array(
                "id" => $record->id,
                "name" => $name,
                "role" => $role,
                "status" => $status,
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
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');

        $input = $request->all();
        $systemUserId = isset($request->id) ? $request->id : 0;

        //Check role has access
        // $hasAccess = $systemUserId>0 ? CheckRoleHasPermission($this->module_name, 'is_update') : CheckRoleHasPermission($this->module_name, 'is_create');
        // if ($hasAccess==0) {
        //     $response = array('status'=>'error', 'message'=>'You do not have access.');
        //     return response()->json($response);   
        // } 
        
        //Get all organization list for Super admin only
        $organizations = [];
        if($loggedUserOrganizationId == 1){
            $organizations = $this->OrganizationModel->GetAllOrganizations();
        }

        // Get all countries

        

        $pageTitle = 'Add User';
        $firstname = "";       
        $lastname = "";       
        $email = "";       
        $mobile_number = "";  
        $role_id = "";  
        $image_name = "";  
        $user_type = "";
        $address = "";
        $city = "";
        $zip = "";
        $stateId = 0;
        $countryId = 0;
        $organization_id = "";
        $countries = $this->CountryModel->GetAllCounries();
      
        if($systemUserId>0)
        {
            $pageTitle = 'Update User';
            $systemUser = $this->UserModel->GetUserDetails($systemUserId);
            if (is_null($systemUser)) {
                return redirect()->back()->with('error_message', 'User does not exist.');
            }

            $firstname = $systemUser->firstname;		
            $lastname = $systemUser->lastname;		
            $email = $systemUser->email;		
            $mobile_number = $systemUser->mobile_number;		
            $role_id = $systemUser->role_id;	
            $image_name = $systemUser->image_name;	
            $user_type = $systemUser->user_type;
            $organization_id = $systemUser->organization_id;
            $address = $systemUser->address;
            $city = $systemUser->city;
            $zip = $systemUser->zip;
            $stateId = $systemUser->state_id;
            $countryId = $systemUser->country_id;
            	
        }
        $this->data['pageTitle'] = $pageTitle;	
        $this->data['firstname'] = $firstname;		
        $this->data['lastname'] = $lastname;		
        $this->data['email'] = $email;		
        $this->data['mobile_number'] = $mobile_number;		
        $this->data['systemUserId'] = $systemUserId;	
        $this->data['role_id'] = $role_id;		
        $this->data['filepath'] = $this->UserModel->GetUserImage($systemUserId, $image_name);
        $this->data['user_type'] = $user_type;
        $this->data['organizations'] = $organizations;
        $this->data['organization_id'] = $organization_id;
        $this->data['loggedUserOrganizationId'] = $loggedUserOrganizationId;  
        $this->data['address'] = $address;
        $this->data['city'] = $city;
        $this->data['zip'] = $zip;
        $this->data['stateId'] = $stateId;
        $this->data['countryId'] = $countryId;   
        $this->data['countries'] = $countries;      
        
        //Get All Roles
        $roles = Role::GetAllRoles($loggedUserOrganizationId);
        $this->data['roles'] = $roles;		

        if($request->ajax())
		{
			$response = array('status'=>'success', 'message'=>'', 'view'=>view('admin.user.create', $this->data)->render());
			return response()->json($response);
		}
      
        return view('admin.user.create',$this->data);
    }    

    public function save(Request $request){
        
        //Get logged user Id
        $loggedUserId = $request->session()->get('LoggedUserId');
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');

        $isAddButtonDisable = 0;
        
        //Get Input
        $input = $request->all();
        $systemUserId = isset($request->id) ? $request->id : 0;
        $organization_id = isset($request->organization_id) ? $request->organization_id : $loggedUserOrganizationId;

        if($systemUserId==0){
            //Validate Input
            $validator = $this->UserModel->ValidateStore($input, $systemUserId, $organization_id);
            if($validator->fails()){
                $response = array('status'=>'error', 'message'=>'Email already exists, try with another.');
                return response()->json($response);
            }       
        }

        //Save Record     
        if($systemUserId==0)  {
            $systemUser = $this->UserModel->SaveUser($input, $input['user_type'], $organization_id);     
        }
        else
        {
            $systemUser = $this->UserModel->GetUserDetails($systemUserId);
            if (is_null($systemUser)) {
                $response = array('status'=>'error', 'message'=>'User does not exist.');
                return response()->json($response);
            }

            $this->UserModel->UpdateUser($input, $systemUserId);     
        }

        //Upload user photo
        if($request->image_name!="")
            $this->UserModel->UploadUserImage($request, $systemUser->id);     

        //Return Message
        $retMessage = $systemUserId ? 'Updated' : 'Added';

        //Check license for organization
        $userCount = $this->UserModel->GetTotalOrgUsersCount($organization_id);
        $settings = $this->OrganizationModel->GetOrganizationSettings($organization_id);
        $userLicense = isset($settings) ? $settings->user_license : 1;
        if($userCount >= $userLicense){
            $isAddButtonDisable = 1;

            $response = array('status'=>'error', 'message'=>'License Error');// Do not change this error message
            return response()->json($response);
        }

        //Return Ajax Request
        if($request->ajax())
		{            
            $response = array('status'=>'success', 'message'=>$retMessage);
			return response()->json($response);
		}         
       
        //Return Output
        return redirect()->route('systemusers')->withSuccess($retMessage);
    }

    public function delete(Request $request)
    {
		try {

            //Check role has access
            // $hasAccess = CheckRoleHasPermission($this->module_name, 'is_delete');
            // if ($hasAccess==0) {
            //     $response = array('status'=>'error', 'message'=>'You do not have access.');
            //     return response()->json($response);   
            // }      
            
            //Get logged user Id
            $loggedUserId = $request->session()->get('LoggedUserId');
			
            //Get Input
            $systemUserId = isset($request->id) ? $request->id : 0;

            //Find Campus
            $systemUser = $this->UserModel->GetUserDetails($systemUserId);
            if (is_null($systemUser)) {
                $response = array('status'=>'error', 'message'=>'User does not exist.');
                return response()->json($response);
            }       
            
            if($systemUser->is_primary_user==1){
                $response = array('status'=>'error', 'message'=>'You cant delete primary user.');
                return response()->json($response);
            }

            //Delete
            $this->UserModel->DeleteOrgSystemUser($systemUserId);

        } catch (Exception $e) {
			if($request->ajax())
			{
				$response = array('status'=>'error', 'message'=>$e->getMessage());
				return response()->json($response);
			}
        }		
		
        //Check license for organization
        $organization_id = $systemUser->organization_id;
        $userCount = $this->UserModel->GetTotalOrgUsersCount($organization_id);
        $settings = $this->OrganizationModel->GetOrganizationSettings($organization_id);
        $userLicense = isset($settings) ? $settings->user_license : 1;
        if($userCount < $userLicense){
            $isAddButtonDisable = 1;

            $response = array('status'=>'success', 'message'=>'License Available');// Do not change this error message
            return response()->json($response);
        }

		$response = array('status'=>'success', 'message'=>'Deleted.');
		return response()->json($response);
    }

    public function changePassword(Request $request)
    {
        //Get logged user Id
        $loggedUserId = $request->session()->get('LoggedUserId');
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');

        $systemUserId = isset($request->id) ? $request->id : 0;

        $this->data['pageTitle'] = 'Change Password';
        $this->data['systemUserId'] = $systemUserId;	
        
        if($request->ajax())
		{
			$response = array('status'=>'success', 'message'=>'', 'view'=>view('admin.user.change_password', $this->data)->render());
			return response()->json($response);
		}
      
        return view('admin.user.change_password',$this->data);
    }   
    
    public function savechangepassword(Request $request){
        
        //Get logged user Id
        $loggedUserId = $request->session()->get('LoggedUserId');
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');

        //Get Input
        $input = $request->all();
        $userId = isset($request->id) ? $request->id : 0;

        $this->UserModel->UpdateUserPassword($input, $userId);     

        //Return Message
        $retMessage = 'Updated';

        //Return Ajax Request
        if($request->ajax())
		{            
            $response = array('status'=>'success', 'message'=>$retMessage);
			return response()->json($response);
		}       
    }

    public function userprofile(Request $request)
    {
        //Get logged user Id
        $loggedUserId = $request->session()->get('LoggedUserId');
        $loggedUserType = $request->session()->get('LoggedUserType');
        $userType = isset($request->user_type) ? $request->user_type : $loggedUserType;
        $pageTitle = 'Edit Profile';
        $firstname = "";       
        $lastname = "";       
        $email = "";       
        $mobile_number = "";  
        $image_name = "";  
        $user_type = "";
        $organization_id = "";
      
        if($loggedUserId>0)
        {
            $pageTitle = 'Edit Profile';
            $user = $this->UserModel->GetUserDetails($loggedUserId);
            if (is_null($user)) {
                return redirect()->back()->with('error_message', 'User does not exist.');
            }

            $firstname = $user->firstname;		
            $lastname = $user->lastname;		
            $email = $user->email;		
            $mobile_number = $user->mobile_number;		
            $role_id = $user->role_id;	
            $image_name = $user->image_name;	
            $user_type = $user->user_type;
            $organization_id = $user->organization_id;
            	
        }
        $this->data['pageTitle'] = $pageTitle;	
        $this->data['firstname'] = $firstname;		
        $this->data['lastname'] = $lastname;		
        $this->data['email'] = $email;		
        $this->data['mobile_number'] = $mobile_number;		
        $this->data['loggedUserId'] = $loggedUserId;	
        $this->data['role_id'] = $role_id;		
        $this->data['filepath'] = $this->UserModel->GetUserImage($loggedUserId, $image_name);
        $this->data['user_type'] = $user_type;
        $this->data['organization_id'] = $organization_id;      
          
        if($userType == 'A'){
            return view('admin.user.admin_profile',$this->data);
        }else{
            return view('admin.user.user_profile',$this->data);
        }
    }    

    public function userprofileupdate(Request $request){
        
        //Get logged user Id
        $loggedUserId = $request->session()->get('LoggedUserId');
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');
        $loggedUserType = $request->session()->get('LoggedUserType');

        //Get Input
        $input = $request->all();

        $user = $this->UserModel->UpdateUser($input, $loggedUserId);     

        //Upload user photo
        if($request->image_name!=""){
            $user = $this->UserModel->UploadUserImage($request, $loggedUserId);  


            $loggedUserPhotoPath = $this->UserModel->GetUserImage($loggedUserId, $user->image_name);
            // Update session value for User info
            session(['LoggedUserName' => $user->firstname." ".$user->lastname, 'LoggedUserPhotoPath' => $loggedUserPhotoPath]);
        }
        

        //Return Message
        $retMessage = 'Updated';
        
        //Return Output
        if($loggedUserType == 'A'){
            return redirect()->route('adminProfile')->withSuccess($retMessage);
        }else{
            return redirect()->route('userprofile')->withSuccess($retMessage);
        }

    }

    public function import(Request $request)
    {
        //Get logged user Id
        $loggedUserId = $request->session()->get('LoggedUserId');
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');

        $input = $request->all();
        $systemUserId = isset($request->id) ? $request->id : 0;

        //Get all organization list for Super admin only
        $organizations = [];
        if($loggedUserOrganizationId == 1){
            $organizations = $this->OrganizationModel->GetAllOrganizations();
        }

        $this->data['pageTitle'] = 'Import User';	
        $this->data['organizations'] = $organizations;
        $this->data['loggedUserOrganizationId'] = $loggedUserOrganizationId;        
        
        if($request->ajax())
		{
			$response = array('status'=>'success', 'message'=>'', 'view'=>view('admin.user.import', $this->data)->render());
			return response()->json($response);
		}
      
        return view('admin.user.import',$this->data);
    } 

    public function userImportPost(Request $request)
    {
        //Get logged user Id
        $loggedUserId = $request->session()->get('LoggedUserId');
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');

        $input = $request->all();
        $organizationId = isset($input['organization_id']) ? $input['organization_id'] : $loggedUserOrganizationId;

        $this->UserModel->UploadImportUsersFile($request);

        return redirect('/users/import/'.$input['user_type'].'/list/'.$organizationId);	
    }

    public function importUserList(Request $request)
    {
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');

        $userType = $request->user_type;
        $organizationId = isset($request->organization_id) ? $request->organization_id : $loggedUserOrganizationId;

        $arrUserList = $this->UserModel->GetAllImportUsersList($organizationId);

        $this->data['formURL'] = '/users/'.$userType.'/importlist/'.$organizationId;
        $this->data['pageTitle'] = 'Import Users';
        $this->data['arrUsers'] = $arrUserList['arrUsers'];
        $this->data['allUsersCount'] = count($arrUserList['arrUsers']);
        $this->data['errorCount'] = $arrUserList['errorCount'];
        $this->data['userType'] = $userType;
        $this->data['organizationId'] = $organizationId;

        return view('admin.user.importlist',$this->data);
    }

    public function postImportUserList(Request $request)
    {
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');

        $userType = $request->user_type;
        $organizationId = isset($request->organization_id) ? $request->organization_id : $loggedUserOrganizationId;

        $arrUserList = $this->UserModel->GetAllImportUsersList($organizationId);
        $users = $arrUserList['arrUsers'];

        foreach($users as $user){
            if($user['errorMessage'] == ''){
                $input['firstname'] = $user['firstname'];
                $input['lastname'] = $user['lastname'];
                $input['email'] = $user['email'];
                $input['mobile_number'] = $user['mobile'];

                $this->UserModel->SaveUser($input, $userType, $organizationId);
            }
        }

        return redirect('/users');	
    }

    public function sendLoginDetailsEmail(Request $request){
        
        //Get logged user Id
        $loggedUserId = $request->session()->get('LoggedUserId');
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');

        $userId = isset($request->id) ? $request->id : 0;

        $user = $this->UserModel->GetUserDetails($userId);
        
        //Send email
        $data = ['ToEmailAddr'=>$user->email, 'ToUserName'=>$user->firstname, 'password' =>$user->normal_password];
        try {
            Mail::to($user->email)->send(new sendlogindetailsEmail($data));
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        //Return Message
        $retMessage = 'Sent';

        //Return Ajax Request
        if($request->ajax())
		{            
            $response = array('status'=>'success', 'message'=>$retMessage);
			return response()->json($response);
		}       
    }

    public function bulkSendLoginDetails(Request $request){
        
        //Get logged user Id
        $loggedUserId = $request->session()->get('LoggedUserId');
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');

        $userIds = isset($request->userIds) ? $request->userIds : [];
		$userIds = $userIds ? explode(',', $userIds) : [];
        foreach ($userIds as $userId) {
            //Check user id  exists
            $userRecord = DB::table('cron_job_send_login_details')->where('user_id', $userId)->where('is_sent', 0)->first();

            if(!$userRecord){
                $data['user_id'] = $userId;
                DB::table('cron_job_send_login_details')->insert($data);
            }            
        }

        //Return Message
        $retMessage = 'Sent';

        //Return Ajax Request
        if($request->ajax())
		{            
            $response = array('status'=>'success', 'message'=>$retMessage);
			return response()->json($response);
		}       
    }

}
