<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\User;
use App\Models\Role;
use App\Models\RolePermission;
use Session;

class OrganizationController extends Controller
{
    var $data = array();
    private $OrganizationModel;

    public function __construct()
    {
        $this->OrganizationModel = new Organization();
    }

    public function organizations(Request $request)
    {
        return view('admin.organization.index');
    }

    /*AJAX request*/
    public function list(Request $request)
    {
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

        // Total records
        $totalRecords = $this->OrganizationModel->GetTotalOrganizationCount();
        $totalRecordswithFilter = $this->OrganizationModel->GetOrganizationCountByFilter($searchValue);

        // Fetch records
        $records = $this->OrganizationModel->GetOrganizationsForDT($columnName, $columnSortOrder,$searchValue,$start, $rowperpage);


        $data_arr = array();
        
        foreach($records as $record){
            
            $action = '<a href="javascript:void(0);" organizationName="'.$record->organization_name.'" organizationId="'.$record->id.'" class="loginAsOrganization btn btn-sm btn-icon item-edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="right" data-bs-html="true" title="Login to organization"><i class="bx bxs-right-arrow-alt"></i></a>';
            $action .= '<a href="/organization/create/'.$record->id.'" class="btn btn-sm btn-icon item-edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="right" data-bs-html="true" title="Edit"><i class="bx bxs-edit"></i></a>';

            $name = '<div class="d-flex justify-content-start align-items-center user-name">
                        <div class="avatar-wrapper">
                        <div class="avatar me-2"><span class="avatar-initial rounded-circle bg-label-danger">'.substr($record->organization_name, 0, 1).'</span></div>
                        </div>
                        <div class="d-flex flex-column"><span class="emp_name text-truncate">'.$record->organization_name.'</span></div>
                    </div>';

            $data_arr[] = array(
                "id" => $record->id,
                "organization_name" => $name,
                "organization_code" => $record->organization_code,
                "email" => $record->email,
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
        $input = $request->all();
        $organizationId = isset($request->id) ? $request->id : 0;

        $page_title = 'Add Organization';
        $name = "";       
        $code = "";       
        $email = "";       
        $phone_number = "";       
        $contact_person_name = "";       
        $contact_person_email = "";       
      
        if($organizationId>0)
        {
            $page_title = 'Update Organization';
            $organization = $this->OrganizationModel->GetOrganizationDetails($organizationId);
            $name = $organization->organization_name;		
            $code = $organization->organization_code;		
            $email = $organization->email;		
            $phone_number = $organization->phone_number;		
            $contact_person_name = $organization->contact_person_name;		
            $contact_person_email = $organization->contact_person_email;		
        }

        $this->data['page_title'] = $page_title;	
        $this->data['name'] = $name;		
        $this->data['code'] = $code;		
        $this->data['email'] = $email;		
        $this->data['phone_number'] = $phone_number;		
        $this->data['contact_person_name'] = $contact_person_name;		
        $this->data['contact_person_email'] = $contact_person_email;		

        $this->data['organizationId'] = $organizationId;		
        

        return view('admin.organization.create',$this->data);
    }
    

    public function save(Request $request){
        
        //Get Input
        $input = $request->all();
        $organizationId = isset($request->id) ? $request->id : 0;

        //Validate Input
        $validator = $this->OrganizationModel->ValidateStoreOrganization($input, $organizationId);
        if($validator->fails()){
            return redirect()->back()->with('error_message', $validator->errors())->withInput();
        }

        //Save Record       
        $organization = $this->OrganizationModel->SaveOrganization($input, $organizationId);      

        //Create System User
        if($organizationId==0) //Only for Add
        {
            //Create System User
            $systeamUser = User::SaveSystemUserWhileCreatingORG($organization->id, $organization->contact_person_name, $organization->contact_person_email);
        
            //Create Role
            $roleInput =[];
            $roleInput['organization_id'] = $organization->id;
            $role = Role::SaveRole($roleInput, 1);

            //Set Permissions
            RolePermission::SetAdministratorRolePermission($role->id);
        }

        //Save Organization Settings
        $settingsInput['user_license'] = 1;
        $OrganizationSettings = $this->OrganizationModel->SaveOrganizationSettings($settingsInput, $organization->id); 

        //Return Output
        $retMessage = $organizationId ? 'Updated' : 'Added';
        return redirect()->route('organizations')->withSuccess($retMessage);
    }

    public function destroy(Request $request)
    {
        //
        $UserId=$request->User_Id;
        $users = $this->userRepository->delete($UserId);
        Session::flash('success','User deleted successfully!');
        return redirect('admin/users');
    }

    public function license(Request $request)
    {
        return view('admin.organization.license');
    }

    /*AJAX request*/
    public function listLicense(Request $request)
    {
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

        // Total records
        $totalRecords = $this->OrganizationModel->GetTotalOrganizationCount();
        $totalRecordswithFilter = $this->OrganizationModel->GetOrganizationCountByFilter($searchValue);

        // Fetch records
        $records = $this->OrganizationModel->GetOrganizationsForDT($columnName, $columnSortOrder,$searchValue,$start, $rowperpage);


        $data_arr = array();
        
        foreach($records as $record){
            //Get organization settings
            $settings = $record->GetOrganizationSettings($record->id);
            $userLicense = isset($settings) ? $settings->user_license : 2;
            $isSubscription = isset($settings) ? $settings->is_subscription : 0;
            $action = '<div class="input-group">
                        <input type="text" class="form-control" id="user_license" name="user_license" isSubscription="'.$isSubscription.'" usersCount="'.$userLicense.'" value="'.$userLicense.'" onblur="updateOrganizationLicense(this, '.$record->id.')">
                        <span class="input-group-text" id="basic-addon13">Users</span>
                    </div>';
            
            $name = '<div class="d-flex justify-content-start align-items-center user-name">
                        <div class="avatar-wrapper">
                        <div class="avatar me-2"><span class="avatar-initial rounded-circle bg-label-danger">'.substr($record->organization_name, 0, 1).'</span></div>
                        </div>
                        <div class="d-flex flex-column"><span class="emp_name text-truncate">'.$record->organization_name.'</span></div>
                    </div>';

            $data_arr[] = array(
                "organization_name" => $name,
                "organization_code" => $record->organization_code,
                "email" => $record->email,
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

    public function saveLicense(Request $request){
        
        //Get Input
        $input = $request->all();
        $organizationId = $input['organization_id'];
        $settings = $this->OrganizationModel->GetOrganizationSettings($organizationId);

        if(!$settings){
            //Save Organization Settings
            $settingsInput['user_license'] = 2;
            $OrganizationSettings = $this->OrganizationModel->SaveOrganizationSettings($settingsInput, $organizationId); 
        }
        $OrganizationSettings = $this->OrganizationModel->UpdateOrganizationSettings($input, $organizationId); 

        //Return Output
        $response = array('status'=>'success', 'message'=>'Updated');
		return response()->json($response);
    }

    public function upgrade(Request $request)
    {
        return view('admin.organization.upgrade');
    }

    public function payment(Request $request)
    {
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');
        $planId = $request->plan_id;

        $this->data['pageTitle'] = "Payment";	
        if($request->ajax())
		{
			$response = array('status'=>'success', 'message'=>'', 'view'=>view('admin.user.create', $this->data)->render());
			return response()->json($response);
		}
      
        return view('admin.user.create',$this->data);
    }
}
