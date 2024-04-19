<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\RolePermission;
use App\Models\Ratings;
use Session;

class RatingsController extends Controller
{
    var $data = array();
    private $CourseModel;
    private $RatingsModel;

    public function __construct()
    {
        $this->CourseModel = new Course();    
        $this->RatingsModel = new Ratings();   
    }

    public function ratings(Request $request)
    {
        return view('admin.courses.ratings');
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
        $totalRecords = $this->RatingsModel->GetTotalRatingsCount();
        $totalRecordswithFilter = $this->RatingsModel->GetCountRatingsByFilter($searchValue);

        // Fetch records
        $records = $this->RatingsModel->GetRatingsForDT($columnName, $columnSortOrder,$searchValue,$start, $rowperpage);


        $data_arr = array();
        
        foreach($records as $record){

            $activeStatusUrl = '/ratings/save/'.$record->id;
            
            $comment = '<div class="d-flex justify-content-start align-items-center user-name">
                        '.$record->comment.'
                    </div>';
            $status =  ($record->is_active==1) ? '<span class="badge bg-label-success"><a href="javascript:void(0);" style="color: #71dd37;" onclick="updateRatingStatus(\''.$activeStatusUrl.'\', 0)" title="Click to inactive rating">Active</a></span>' : '<span class="badge bg-label-warning"><a href="javascript:void(0);" style="color: #ffab00;" onclick="updateRatingStatus(\''.$activeStatusUrl.'\', 1)" title="Click to active rating">Inactive</a></span>';

            $data_arr[] = array(
                "id" => $record->id,
                "comment" => $comment,
                "status" => $status
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


    public function save(Request $request){
        ini_set('max_execution_time', 0);     
        //Get Input
        $input = $request->all();
        $ratingId = isset($request->id) ? $request->id : 0;

        //Save Record       
        $rating = $this->RatingsModel->UpdateRatingStatus($input, $ratingId);      

        //Return Output
        $retMessage = 'Updated';

        //Return Ajax Request
        if($request->ajax())
		{            
            $response = array('status'=>'success', 'message'=>$retMessage);
			return response()->json($response);
		}

    }

    public function destroy(Request $request)
    {
        //
        $UserId=$request->User_Id;
        $users = $this->userRepository->delete($UserId);
        Session::flash('success','User deleted successfully!');
        return redirect('admin/users');
    }

    public function courseplay(Request $request)
    {
        $courseId = isset($request->id) ? $request->id : 0;

        $page_title = 'Play Course';
        $course = $this->CourseModel->GetCourseDetails($courseId);
        //Get Course play url
        $coursePlayUrl = '';
        $scormPackagePlayUrl = env('APP_URL').'/uploads/courses/'.$course->id.'/scormcontent/index.html';
        $scorm2004PlayUrl = env('APP_URL').'/uploads/courses/'.$course->id.'/scormcontent/index.html';
        $scormHTML5PlayUrl = env('APP_URL').'/uploads/courses/'.$course->id.'/content/index.html';
        $scormStoryPlayUrl = env('APP_URL').'/uploads/courses/'.$course->id.'/story.html';

        $headers1 = @get_headers($scormPackagePlayUrl);
        $headers2 = @get_headers($scorm2004PlayUrl);
        $headers3 = @get_headers($scormHTML5PlayUrl);
        $headers4 = @get_headers($scormStoryPlayUrl);

        if ($headers1 && strpos($headers1[0], '200')) {
            $coursePlayUrl = $scormPackagePlayUrl;
        }else if($headers2 && strpos($headers2[0], '200')){
            $coursePlayUrl = $scorm2004PlayUrl; 
        }else if($headers3 && strpos($headers3[0], '200')){
            $coursePlayUrl = $scormHTML5PlayUrl;   
        }else if($headers4 && strpos($headers4[0], '200')){
            $coursePlayUrl = $scormStoryPlayUrl;   
        }

        $layout = 'layouts.masterLayout';

        //layout
        if($request->session()->get('LoggedUserType') == 'T'){
            $layout = 'layouts.user_layout';
        }
        
        $this->data['pageTitle'] = $page_title;	
        $this->data['course'] = $course;		
        $this->data['coursePlayUrl'] = $coursePlayUrl;	
        $this->data['courseId'] = $courseId;
        $this->data['layout'] = $layout;	

        return view('admin.courses.playcourse',$this->data);
    }

    public function courseRatings(Request $request)
    {
        $loggedUserId = $request->session()->get('LoggedUserId');
        $courseId = $request->id;

        //get Course ratings
        $ratings = $this->CourseModel->GetAllUsersActiveRatingsByCourseId($courseId);    

        //get logged user ratring points
        $currentUserRating = $this->RatingsModel->GetRatingDetailsByUserId($loggedUserId, $courseId);    

        $this->data['pageTitle'] = 'Ratings';	
        $this->data['courseId'] = $courseId;
        $this->data['ratings'] = $ratings;
        $this->data['currentUserRating'] = $currentUserRating;
        $this->data['currentUserRatingPoints'] = isset($currentUserRating->rating_points) ? $currentUserRating->rating_points : 0;
        $this->data['loggedUserId'] = $loggedUserId;
        
        if($request->ajax())
		{
			$response = array('status'=>'success', 'message'=>'', 'view'=>view('ratings', $this->data)->render());
			return response()->json($response);
		}
      
        return view('ratings',$this->data);
    } 
    
    public function postRatings(Request $request){  
        $loggedUserId = $request->session()->get('LoggedUserId');
        //Get Input
        $input = $request->all();
        $courseId = isset($request->id) ? $request->id : 0;

        //Check already exists record
        $ratings = $this->RatingsModel->GetRatingDetailsByCourseIdUserId($courseId, $loggedUserId); 
        if($ratings){
            //Update Record       
            $ratings = $this->RatingsModel->UpdateRating($input, $courseId, $loggedUserId);  
        }else{
            //Save Record       
            $ratings = $this->RatingsModel->SaveRatings($input, $courseId, $loggedUserId);   
        }
           
        //Return Output
        $retMessage = 'Added';

        //Return Ajax Request
        if($request->ajax())
		{            
            $response = array('status'=>'success', 'message'=>$retMessage);
			return response()->json($response);
		}
        
        return redirect()->route('courses')->withSuccess($retMessage);
    }

}
