<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
use Session;

class RoleController extends Controller
{
    var $data = array();
    private $RoleModel;
    private $module_name;

    public function __construct()
    {
        $this->RoleModel = new Role();
        $this->module_name = "role";
    }

    public function roles(Request $request)
    {
        //Check role has access
        $hasAccess = CheckRoleHasPermission($this->module_name, 'is_read');
        if ($hasAccess==0) {
            return redirect()->back()->withError('You do not have access.');
        }        

        return view('admin.role.index');
    }

    /*AJAX request*/
    public function list(Request $request)
    {
        //Get logged user Id
        $loggedUserId = $request->session()->get('LoggedUserId');
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');

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
        $totalRecords = $this->RoleModel->GetTotalRoleCount($loggedUserOrganizationId);
        $totalRecordswithFilter = $this->RoleModel->GetRoleCountByFilter($searchValue, $loggedUserOrganizationId);

        //Fetch records
        $records = $this->RoleModel->GetRolesForDT($loggedUserOrganizationId, $columnName, $columnSortOrder,$searchValue,$start, $rowperpage);

        $data_arr = array();
        
        foreach($records as $record){
          
            $editUrl = "/admin/role/create/".$record->id;
            $deleteUrl = "/admin/role/delete/".$record->id;
            $action = '<a href="javascript:void(0);" onclick="openCreateRoleModal(\''.$editUrl.'\')" class="btn btn-sm btn-icon item-edit"><i class="bx bxs-edit"></i></a>';
            $action .= '<a href="javascript:void(0);" onclick="deleteRole(this, \''.$deleteUrl.'\')" class="btn btn-sm btn-icon item-edit"><i class="bx bx-trash"></i></a>';

            $name = '<div class="d-flex justify-content-start align-items-center user-name">
                        <div class="avatar-wrapper">
                        <div class="avatar me-2"><span class="avatar-initial rounded-circle bg-label-danger">'.substr($record->name, 0, 1).'</span></div>
                        </div>
                        <div class="d-flex flex-column"><span class="emp_name text-truncate">'.$record->name.'</span></div>
                    </div>';

            $data_arr[] = array(
                "id" => $record->id,
                "name" => $name,
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
        $roleId = isset($request->id) ? $request->id : 0;

         //Check role has access
         $hasAccess = $roleId>0 ? CheckRoleHasPermission($this->module_name, 'is_update') : CheckRoleHasPermission($this->module_name, 'is_create');
         if ($hasAccess==0) {
             $response = array('status'=>'error', 'message'=>'You do not have access.');
             return response()->json($response);   
         }      

        $pageTitle = 'Add Role';
        $name = "";       
      
        if($roleId>0)
        {
            $pageTitle = 'Update Role';
            $role = $this->RoleModel->GetRoleDetails($roleId);
            if (is_null($role)) {
                $response = array('status'=>'error', 'message'=>'Role does not exist.');
                return response()->json($response);
            }

            $name = $role->name;		
        }

        //Get All Permissions
        $permissions = Permission::GetAllPermissions();

        $this->data['pageTitle'] = $pageTitle;	
        $this->data['name'] = $name;		
        $this->data['roleId'] = $roleId;	
        $this->data['permissions'] = $permissions;			

        if($request->ajax())
		{
			$response = array('status'=>'success', 'message'=>'', 'view'=>view('admin.role.create', $this->data)->render());
			return response()->json($response);
		}
      
        return view('admin.role.create',$this->data);
    }
    

    public function save(Request $request){
        
        //Get logged user Id
        $loggedUserId = $request->session()->get('LoggedUserId');
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');

         //Get Input
         $input = $request->all();
         $roleId = isset($request->id) ? $request->id : 0;

        //Validate Input
        $validator = $this->RoleModel->ValidateStore($input, $roleId, $loggedUserOrganizationId);
        if($validator->fails()){
            $response = array('status'=>'error', 'message'=>'Name already exists, try with another.');
            return response()->json($response);
        }       

        //Save Record     
        if($roleId==0)  {  
            $input['organization_id'] = $loggedUserOrganizationId;  
            $input['created_by'] = $loggedUserId;       
            $role = $this->RoleModel->SaveRole($input);     
        }
        else
        {
            $role = $this->RoleModel->GetRoleDetails($roleId);
            if (is_null($role)) {
                $response = array('status'=>'error', 'message'=>'Role does not exist.');
                return response()->json($response);
            }
           
            $role = $this->RoleModel->UpdateRole($input, $roleId);    
        }

           //Set Role Permissions
        $role_permission = RolePermission::SetRolePermission($input,$role->id);   

        //Return Message
        $retMessage = $roleId ? 'Updated' : 'Added';

        //Return Ajax Request
        if($request->ajax())
		{            
            $response = array('status'=>'success', 'message'=>$retMessage);
			return response()->json($response);
		}         
       
        //Return Output
        return redirect()->route('role')->withSuccess($retMessage);
    }

    public function delete(Request $request)
    {
		try {

            //Check role has access
            $hasAccess = CheckRoleHasPermission($this->module_name, 'is_delete');
            if ($hasAccess==0) {
                $response = array('status'=>'error', 'message'=>'You do not have access.');
                return response()->json($response);   
            }      
            
            //Get logged user Id
            $loggedUserId = $request->session()->get('LoggedUserId');
			
            //Get Input
            $roleId = isset($request->id) ? $request->id : 0;

            //Find role
            $role = $this->RoleModel->GetRoleDetails($roleId);
            if (is_null($role)) {
                $response = array('status'=>'error', 'message'=>'Role does not exist.');
                return response()->json($response);
            }

            if($role->is_primary_role==1){
                $response = array('status'=>'error', 'message'=>'You cant delete primary role.');
                return response()->json($response);
            }

            //Delete
            $this->RoleModel->DeleteRole($roleId);

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
  
}
