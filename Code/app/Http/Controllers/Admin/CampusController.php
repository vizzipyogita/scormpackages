<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\User;
use App\Models\Campus;
use Session;

class CampusController extends Controller
{
    var $data = array();
    private $CampusModel;
    private $module_name;

    public function __construct()
    {
        $this->CampusModel = new Campus();
        $this->module_name = "campus";
    }

    public function campus(Request $request)
    {
        //Check role has access
        $hasAccess = CheckRoleHasPermission($this->module_name, 'is_read');
        if ($hasAccess==0) {
            return redirect()->back()->withError('You do not have access.');
        }        

        return view('admin.campus.index');
    }

    /*AJAX request*/
    public function list(Request $request)
    {
        //Get logged user Id
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

        // Total records
        $totalRecords = $this->CampusModel->GetTotalCampusCount($loggedUserId);
        $totalRecordswithFilter = $this->CampusModel->GetCampusCountByFilter($searchValue, $loggedUserId);

        // Fetch records
        $records = $this->CampusModel->GetCampusForDT($loggedUserId, $columnName, $columnSortOrder,$searchValue,$start, $rowperpage);


        $data_arr = array();
        
        foreach($records as $record){
          
            $editUrl = "/admin/campus/create/".$record->id;
            $deleteUrl = "/admin/campus/delete/".$record->id;
            $action = '<a href="javascript:void(0);" onclick="openCreateCampusModal(\''.$editUrl.'\')" class="btn btn-sm btn-icon item-edit"><i class="bx bxs-edit"></i></a>';
            $action .= '<a href="javascript:void(0);" onclick="deleteCampus(this, \''.$deleteUrl.'\')" class="btn btn-sm btn-icon item-edit"><i class="bx bx-trash"></i></a>';

            $name = '<div class="d-flex justify-content-start align-items-center user-name">
                        <div class="avatar-wrapper">
                        <div class="avatar me-2"><span class="avatar-initial rounded-circle bg-label-danger">'.substr($record->organization_name, 0, 1).'</span></div>
                        </div>
                        <div class="d-flex flex-column"><span class="emp_name text-truncate">'.$record->organization_name.'</span></div>
                    </div>';

            $data_arr[] = array(
                "id" => $record->id,
                "name" => $name,
                "code" => $record->organization_code,
                "code_expire_date" => $record->code_expire_date,
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
        $campusId = isset($request->id) ? $request->id : 0;

        //Check role has access
        $hasAccess = $campusId>0 ? CheckRoleHasPermission($this->module_name, 'is_update') : CheckRoleHasPermission($this->module_name, 'is_create');
        if ($hasAccess==0) {
            $response = array('status'=>'error', 'message'=>'You do not have access.');
            return response()->json($response);   
        }      

        $pageTitle = 'Add Campus';
        $name = "";       
      
        if($campusId>0)
        {
            $pageTitle = 'Update Campus';
            $campus = $this->CampusModel->GetLoggedUserCampusDetails($campusId, $loggedUserId);
            if (is_null($campus)) {
                $response = array('status'=>'error', 'message'=>'Campus does not exist.');
                return response()->json($response);                
            }

            $name = $campus->organization_name;		
        }

        $this->data['pageTitle'] = $pageTitle;	
        $this->data['name'] = $name;		
        $this->data['campusId'] = $campusId;		

        if($request->ajax())
		{
			$response = array('status'=>'success', 'message'=>'', 'view'=>view('admin.campus.create', $this->data)->render());
			return response()->json($response);
		}
      
        return view('admin.campus.create',$this->data);
    }
    

    public function save(Request $request){
        
        //Get logged user Id
        $loggedUserId = $request->session()->get('LoggedUserId');

        //Get Input
        $input = $request->all();
        $campusId = isset($request->id) ? $request->id : 0;

        //Save Record     
        if($campusId==0)  {
            $campusNames = $request->organization_name;
            $this->CampusModel->SaveCampus($campusNames, $loggedUserId);     
        }
        else
        {
            $campus = $this->CampusModel->GetLoggedUserCampusDetails($campusId, $loggedUserId);
            if (is_null($campus)) {
                $response = array('status'=>'error', 'message'=>'Campus does not exist.');
                return response()->json($response);
            }
            $campus->organization_name = $input['organization_name'];
            $campus->save();
        }

        //Return Message
        $retMessage = $campusId ? 'Updated' : 'Added';

        //Return Ajax Request
        if($request->ajax())
		{            
            $response = array('status'=>'success', 'message'=>$retMessage);
			return response()->json($response);
		}         
       
        //Return Output
        return redirect()->route('campus')->withSuccess($retMessage);
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
            $campusId = isset($request->id) ? $request->id : 0;

            //Find Campus
            $campus = $this->CampusModel->GetLoggedUserCampusDetails($campusId, $loggedUserId);
            if (is_null($campus)) {
                $response = array('status'=>'error', 'message'=>'Campus does not exist.');
                return response()->json($response);
            }

            //Delete
            $this->CampusModel->DeleteCampus($campusId);

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
