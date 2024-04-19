<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\User;
use App\Models\Role;
use App\Models\RolePermission;
use Session;

class CourseCategoryController extends Controller
{
    var $data = array();
    private $CourseCategoryModel;

    public function __construct()
    {
        $this->CourseCategoryModel = new CourseCategory();     
    }

    public function coursecategory(Request $request)
    {
        return view('admin.coursecategory.index');
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
        $totalRecords = $this->CourseCategoryModel->GetTotalCategoryCount();
        $totalRecordswithFilter = $this->CourseCategoryModel->GetCategoryCountByFilter($searchValue);

        // Fetch records
        $records = $this->CourseCategoryModel->GetCategoryForDT($columnName, $columnSortOrder,$searchValue,$start, $rowperpage);


        $data_arr = array();
        
        foreach($records as $record){
            $editUrl = "/coursecategory/create/".$record->id;
            $action = '<a href="javascript:void(0);" onclick="openCreateCourseCategoryModal(\''.$editUrl.'\')" class="btn btn-sm btn-icon item-edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="right" data-bs-html="true" title="Edit"><i class="bx bxs-edit"></i></a>';
           
            $title = '<div class="d-flex justify-content-start align-items-center user-name">
                        <div class="avatar-wrapper">
                        <div class="avatar me-2"><span class="avatar-initial rounded-circle bg-label-danger">'.substr($record->title, 0, 1).'</span></div>
                        </div>
                        <div class="d-flex flex-column"><span class="emp_name text-truncate">'.$record->title.'</span></div>
                    </div>';
            $status =  ($record->is_active==1) ? '<span class="badge bg-label-success">Active</span>' : '<span class="badge bg-label-warning">Inactive</span>';

            $data_arr[] = array(
                "id" => $record->id,
                "title" => $title,
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
        $input = $request->all();
        $courseCategoryId = isset($request->id) ? $request->id : 0;

        $page_title = 'Add Course Category';
        $title = "";         
       
        if($courseCategoryId>0)
        {
            $page_title = 'Update Course Category';
            $category = $this->CourseCategoryModel->GetCategoryDetails($courseCategoryId);
            $title = $category->title;		
        }

        $this->data['pageTitle'] = $page_title;	
        $this->data['title'] = $title;		

        $this->data['courseCategoryId'] = $courseCategoryId;		
        
        if($request->ajax())
		{
			$response = array('status'=>'success', 'message'=>'', 'view'=>view('admin.coursecategory.create', $this->data)->render());
			return response()->json($response);
		}

        return view('admin.coursecategory.create',$this->data);
    }
    

    public function save(Request $request){
        
        //Get Input
        $input = $request->all();
        $courseId = isset($request->id) ? $request->id : 0;

        // $this->CourseCategoryModel->UploadCourseZip($request); 
        // exit;
        //Save Record       
        $course = $this->CourseCategoryModel->SaveCategogry($input, $courseId);      

        //Return Output
        $retMessage = $courseId ? 'Updated' : 'Added';

        //Return Ajax Request
        if($request->ajax())
		{            
            $response = array('status'=>'success', 'message'=>$retMessage);
			return response()->json($response);
		}

        
        return redirect()->route('coursecategory')->withSuccess($retMessage);
    }

    public function destroy(Request $request)
    {
        //
        $UserId=$request->User_Id;
        $users = $this->userRepository->delete($UserId);
        Session::flash('success','User deleted successfully!');
        return redirect('/coursecategory');
    }

}
