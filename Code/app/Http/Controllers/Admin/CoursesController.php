<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\User;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Ratings;
use Session;

class CoursesController extends Controller
{
    var $data = array();
    private $CourseModel;
    private $RatingsModel;

    public function __construct()
    {
        $this->CourseModel = new Course();   
        $this->CourseCategoryModel = new CourseCategory();   
        $this->RatingsModel = new Ratings();   
    }

    public function courses(Request $request)
    {
        return view('admin.courses.index');
    }

    /*AJAX request*/
    public function list(Request $request)
    {
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

        // Total records
        $totalRecords = $this->CourseModel->GetTotalCourseCount();
        $totalRecordswithFilter = $this->CourseModel->GetCourseCountByFilter($searchValue);

        // Fetch records
        $records = $this->CourseModel->GetCoursesForDT($columnName, $columnSortOrder,$searchValue,$start, $rowperpage);


        $data_arr = array();
        
        foreach($records as $record){
            //Get course play url 
            // $startCourseUrl = '/uploads/courses/'.$record->id.'/scormcontent/index.html';
            $startCourseUrl = $this->CourseModel->GetCoursePlayUrl($record->id); //'/uploads/courses/'.$record->id.'/scormcontent/index.html';
            $editCourseUrl = '/courses/create/'.$record->id;
            $action ='';
            if($loggedUserOrganizationId == 1){
                $action .= '<a href="javascript:void(0);" onclick="openCreateCourseModal(\''.$editCourseUrl.'\')" class="btn btn-sm btn-icon item-edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="right" data-bs-html="true" title="Edit"><i class="bx bxs-edit"></i></a>';
            }
            $action .='<a href="'.$startCourseUrl.'" class="btn btn-sm btn-icon item-edit" target="_blank"><i class="bx bx-show"></i></a>';

            $title = '<div class="d-flex justify-content-start align-items-center user-name">
                        <div class="avatar-wrapper">
                        <div class="avatar me-2"><span class="avatar-initial rounded-circle bg-label-danger">'.substr($record->title, 0, 1).'</span></div>
                        </div>
                        <div class="d-flex flex-column"><span class="emp_name text-truncate">'.$record->title.'</span></div>
                    </div>';
            $status =  ($record->is_active==1) ? '<span class="badge bg-label-success">Active</span>' : '<span class="badge bg-label-warning">Inactive</span>';

            $data_arr[] = array(
                "sortorder" => $record->sortorder,
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
        $courseId = isset($request->id) ? $request->id : 0;

        $page_title = 'Add Course';
        $title = "";       
        $description = ""; 
        $categoryId = 0; 
        $sortorder ='';      
       
        if($courseId>0)
        {
            $page_title = 'Update Course';
            $course = $this->CourseModel->GetCourseDetails($courseId);
            $title = $course->title;		
            $description = $course->description;
            $categoryId = $course->category_id;
            $sortorder =$course->sortorder;
        }

        // Category List
        $courseCategories = $this->CourseCategoryModel->GetAllCategories();
        $this->data['pageTitle'] = $page_title;	
        $this->data['title'] = $title;		
        $this->data['description'] = $description;	
        $this->data['categoryId'] = $categoryId;
        $this->data['sortorder'] = $sortorder;

        $this->data['courseId'] = $courseId;		
        $this->data['courseCategories'] = $courseCategories;
        
        if($request->ajax())
		{
			$response = array('status'=>'success', 'message'=>'', 'view'=>view('admin.courses.create', $this->data)->render());
			return response()->json($response);
		}

        return view('admin.courses.create',$this->data);
    }
    

    public function save(Request $request){
        ini_set('max_execution_time', 0);     
        //Get Input
        $input = $request->all();
        $courseId = isset($request->id) ? $request->id : 0;

        //Save Record       
        $course = $this->CourseModel->SaveCourse($input, $courseId);      

        // upload course image
        if($request->image_name!="")
            $this->CourseModel->UploadCourseImage($request, $course->id);   

        // upload zip file
        if($course){
            if($request->file_name!="")
                $this->CourseModel->UploadCourseZip($request, $course->id); 
        }

        //Return Output
        $retMessage = $courseId ? 'Updated' : 'Added';

        //Return Ajax Request
        if($request->ajax())
		{            
            $response = array('status'=>'success', 'message'=>$retMessage);
			return response()->json($response);
		}

        
        return redirect()->route('courses')->withSuccess($retMessage);
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
        $loggedUserId = $request->session()->get('LoggedUserId');
        $courseId = isset($request->id) ? $request->id : 0;

        $page_title = 'Play Course';
        $course = $this->CourseModel->GetCourseDetails($courseId);
        //Set user course status
        $courseStatus = \DB::table('user_course_status')->where('user_id', $loggedUserId)->where('course_id', $courseId)->first();
        if(!$courseStatus){
            \DB::table('user_course_status')->insert(array('user_id'=>$loggedUserId, 'course_id' => $courseId));
        }
        
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
        // $coursePlayUrl = $scormPackagePlayUrl;
        $layout = 'layouts.user_layout';

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

        $course = $this->CourseModel->GetCourseDetails($courseId);

        //get Course ratings
        $ratings = $this->CourseModel->GetAllUsersActiveRatingsByCourseId($courseId, $limit=3);   
        
        // Get course active rating count
        $activeRatingsCount = $this->RatingsModel->GetTotalActiveRatingsCount($courseId);

        //get logged user ratring points
        $currentUserRating = $this->RatingsModel->GetRatingDetailsByUserId($loggedUserId, $courseId); 
        
        //Get user course status
        $isStartCourse = 0;
        $courseStatus = \DB::table('user_course_status')->where('user_id', $loggedUserId)->where('course_id', $courseId)->first();
        if($courseStatus){
            $isStartCourse = 1;
        }

        $this->data['pageTitle'] = 'Ratings';	
        $this->data['courseId'] = $courseId;
        $this->data['ratings'] = $ratings;
        $this->data['course'] = $course;
        $this->data['currentUserRating'] = $currentUserRating;
        $this->data['currentUserRatingPoints'] = isset($currentUserRating->rating_points) ? $currentUserRating->rating_points : 0;
        $this->data['loggedUserId'] = $loggedUserId;
        $this->data['activeRatingsCount'] = $activeRatingsCount;
        $this->data['isStartCourse'] = $isStartCourse;
        
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

    public function userAllCourses(Request $request)
    {
        $loggedUserId = $request->session()->get('LoggedUserId');
        //Get Query Param
        $categoryId = $request->id;
        $title = isset($request->title) ? $request->title : '';
        $lastCategoryId = isset($request->lastCategoryId) ? $request->lastCategoryId : 0;
        // Category List
        $courseCategories = $this->CourseCategoryModel->GetAllCategories();
        //get all courses
        $courses = $this->CourseModel->GetAllCourses($categoryId, $title, $limit=0);
        $this->data['pageTitle'] = 'Courses';	
        $this->data['courses'] = $courses;	
        $this->data['courseCategories'] = $courseCategories;	
        $this->data['categoryId'] = $categoryId;	
        $this->data['title'] = $title;
        $this->data['loggedUserId'] = $loggedUserId;	

        if($request->ajax())
		{
			$response = array('status'=>'success', 'message'=>'', 'view'=>view('course_item_box', $this->data)->render());
			return response()->json($response);
		}

        return view ('view_all_category_courses',$this->data);
    }

    public function userAllCourseRatings(Request $request)
    {
        $loggedUserId = $request->session()->get('LoggedUserId');
        //Get Query Param
        $courseId = $request->id;
        $lastRatingId = isset($request->lastRatingId) ? $request->lastRatingId : 0;

        //Get Course Details
        $course = $this->CourseModel->GetCourseDetails($courseId);

        $courseRatingAvg = $course->getCourseCalculatedRatings($course->id);
        $courseRatingCount = $course->getCourseRatingCount($course->id);
        
        //get all courses
        $ratings = $this->CourseModel->GetAllUsersActiveRatingsByCourseId($courseId, $limit=0);

        $this->data['pageTitle'] = $course->title.' Ratings';	
        $this->data['ratings'] = $ratings;		
        $this->data['courseId'] = $courseId;
        $this->data['loggedUserId'] = $loggedUserId;	
        $this->data['courseRatingAvg'] = $courseRatingAvg;
        $this->data['courseRatingCount'] = $courseRatingCount;

        if($request->ajax())
		{
			$response = array('status'=>'success', 'message'=>'', 'view'=>view('course_rating_item_box', $this->data)->render());
			return response()->json($response);
		}

        return view ('view_all_course_ratings',$this->data);
    }

    public function courseFavorite(Request $request){  
        $loggedUserId = $request->session()->get('LoggedUserId');
        //Get Input
        $input = $request->all();
        $courseId = isset($request->id) ? $request->id : 0;

        //Check already exists record
        $isFavorite = $this->CourseModel->CheckFavoriteCourse($courseId, $loggedUserId); 
        if($isFavorite == 1){
            //Remove from favorite       
            \DB::table('user_favorite_course')->where('course_id', $courseId)->where('user_id', $loggedUserId)->delete();
            $isFavorite == 0;
        }else{
            //Save Record       
            \DB::table('user_favorite_course')->insert(array('course_id'=>$courseId, 'user_id'=>$loggedUserId)); 
            $isFavorite == 1;
        }
        $isFavorite = $this->CourseModel->CheckFavoriteCourse($courseId, $loggedUserId); 
        //Return Output
        $retMessage = 'Added';

        //Return Ajax Request
        if($request->ajax())
		{            
            $response = array('status'=>'success', 'isFavorite'=>$isFavorite, 'message'=>$retMessage);
			return response()->json($response);
		}
        
    }

    public function finishCourse(Request $request){  
        $loggedUserId = $request->session()->get('LoggedUserId');
        //Get Input
        $courseId = isset($request->id) ? $request->id : 0;

        //Set user course status
        $courseStatus = \DB::table('user_course_status')->where('user_id', $loggedUserId)->where('course_id', $courseId)->first();
        if($courseStatus){
            \DB::table('user_course_status')->where('user_id', $loggedUserId)
                                            ->where('course_id', $courseId)
                                            ->update(array('is_complete'=>1));
        }

        //Return Output
        $retMessage = 'Completed';

        //Return Ajax Request
        if($request->ajax())
		{            
            $response = array('status'=>'success', 'message'=>$retMessage);
			return response()->json($response);
		}
        
    }

    public function userHistory(Request $request)
    {
        $loggedUserId = $request->session()->get('LoggedUserId');
        $isInprogress = isset($request->isInprogress) ? $request->isInprogress : 0;     
        //get all inprogress courses
        $inprogressCourses = $this->CourseModel->GetUserAllInprogressCourses($loggedUserId); 
        $completedCourses = $this->CourseModel->GetUserAllCompletedCourses($loggedUserId); 
        $favoriteCourses = $this->CourseModel->GetUserAllFavoriteCourses($loggedUserId); 
        

        $this->data['pageTitle'] = 'History';	
        $this->data['courses'] = [];
        $this->data['loggedUserId'] = $loggedUserId;	
        $this->data['inprogressCourses'] = $inprogressCourses;	
        $this->data['completedCourses'] = $completedCourses;	
        $this->data['favoriteCourses'] = $favoriteCourses;	
        $this->data['isInprogress'] = $isInprogress;	

        return view ('user_history',$this->data);
    }

}
