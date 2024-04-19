<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;
use ZipArchive;
use File;
use App\Models\Ratings;

class Course extends Model
{
    use HasFactory;
    public $timestamps = true;    
    protected $table = 'courses';
    protected $fillable = [
        'user_id', 
        'category_id',
        'title',
        'description',
        'file_name',
        'is_active',
        'sortorder'
    ];   
    
    public function Category()
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }

    public static function GetAllCourses($categoryId=0, $title='', $limit=0){        
        $courses = Course::where('title', '<>', '')->where('is_active', 1);
                if($categoryId > 0){
                    $courses = $courses->where('courses.category_id', $categoryId);
                }
                if($title !=''){
                    $courses = $courses->where('courses.title', 'like', '%' .$title . '%');
                }
                if($limit > 0){
                    $courses = $courses->limit($limit);
                }
                
                $courses = $courses->orderby('sortorder', 'ASC')->get();

        return $courses;
    }

    public static function SaveCourse($input, $id=0){
        $course = new Course;

        if($id>0)
            $course = Course::find($id);

        $course->title = $input['title'];
        $course->description = $input['description'];
        $course->category_id = isset($input['category_id']) ? $input['category_id'] : 0;
        $course->is_active  = isset($input['is_active']) ? $input['is_active'] : 1;
        $course->sortorder  = isset($input['sortorder']) ? $input['sortorder'] : '';
        
        $course->save();

       
        return $course;
    }


    public static function GetCourseDetails($id){        
        $course = Course::find($id);
        return $course;
    }

    public static function GetTotalCourseCount(){        
        $count = Course::where('title', '<>', '')->count();
        return $count;
    }

    public static function GetCourseCountByFilter($searchValue){        
        $count = Course::where('title', 'like', '%' .$searchValue . '%')->count();
        return $count;
    }

    public static function GetCoursesForDT($columnName, $columnSortOrder,$searchValue,$start, $rowperpage)
    {        
        $records = Course::orderBy($columnName,$columnSortOrder)
                                ->where('courses.title', 'like', '%' .$searchValue . '%')
                                ->select('courses.*')
                                ->skip($start)
                                ->take($rowperpage)
                                ->get();
        return $records;
    }

    public static function UploadCourseZip($input, $id=2){       
        ini_set('max_execution_time', 0);      
        //Upload Zip
		$zipFile = $input->file('file_name');
		$destinationPath = public_path('/uploads/courses/'.$id);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
		$file_name = rand().'.'.$zipFile->getClientOriginalExtension(); 
		$zipFile->move($destinationPath, $file_name);
        
        $zip = new ZipArchive;
        if ($zip->open($destinationPath.'/'.$file_name) === TRUE) {
            $zip->extractTo($destinationPath.'/');
            $zip->close();
        } 
			
        return true;
    }

    public static function UploadCourseImage($input, $id){       
        
        //Find Course
        $course = Course::find($id);

        //Delete old image 
        if(isset($course->image_name)){
            if($course->image_name!="")
            {
                $filename = public_path('/uploads/courses/'.$id.'/'.$course->image_name);
                if(File::exists($filename)) 
                    File::delete($filename);                
            }
        }
             
        //Upload Image
		$image = $input->file('image_name');
		$destinationPath = public_path('/uploads/courses/'.$id);
		$image_name = rand().'.'.$image->getClientOriginalExtension(); 
		$image->move($destinationPath, $image_name);
		
		//Update Image name
        $course->image_name = $image_name; 
        $course->save();
	
        return true;
    }

    public static function getCourseImage($id, $image_name=""){
        $filepath = '/assets/img/user_img/Administrative1.png';

        if($image_name!="")
            $filepath = '/uploads/courses/'.$id.'/'.$image_name;
        else 
            $filepath = '/assets/img/user_img/Administrative1.png';

        return $filepath;
    }

    public static function getCourseCalculatedRatings($id){ 
        $ratingAvg = 0;
        $ratingPoints = Ratings::where('course_id', $id)->sum('rating_points');
        // calculate ratings
        $noOfRatings = Ratings::where('course_id', $id)->count();

        if($noOfRatings > 0){
            $ratingAvg = $ratingPoints/$noOfRatings;
        }
        
        return $ratingAvg;
    }

    public static function getCourseRatingCount($courseId){ 
        $count = Ratings::where('course_id', $courseId)->where('comment', '!=', '')->where('is_active', 1)->count();
        return $count;
    }

    public static function GetAllUsersActiveRatingsByCourseId($courseId, $limit=0){

        $ratings = Ratings::join('users', 'users.id', '=', 'users_course_ratings.user_id')
                ->select('users.*', 'users_course_ratings.*')
                ->where('users_course_ratings.course_id', $courseId)
                ->where('users_course_ratings.is_active', 1);

                if($limit > 0){
                    $ratings = $ratings->limit($limit);
                }
                $ratings = $ratings->get();
        
        return $ratings;
    }

    public static function GetCoursePlayUrl($courseId){ 
        ini_set('max_execution_time', 0);  
        
        $coursePlayUrl = '';
        $scormPackagePlayUrl = env('APP_URL').'/uploads/courses/'.$courseId.'/scormcontent/index.html';
        $scorm2004PlayUrl = env('APP_URL').'/uploads/courses/'.$courseId.'/scormcontent/index.html';
        $scormHTML5PlayUrl = env('APP_URL').'/uploads/courses/'.$courseId.'/content/index.html';
        $scormStoryPlayUrl = env('APP_URL').'/uploads/courses/'.$courseId.'/story.html';

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
        
        return $coursePlayUrl;
    }

    public static function CheckFavoriteCourse($courseId, $userId){
        $isFavorite = 0;
        $favorite = \DB::table('user_favorite_course')->where('course_id', $courseId)->where('user_id', $userId)->first();
        if($favorite){
            $isFavorite = 1;
        }
        return $isFavorite;
    }

    public static function GetUserAllInprogressCourses($userId, $limit=0){

        $courses = Course::join('user_course_status', 'courses.id', '=', 'user_course_status.course_id')
                ->select('courses.*')
                ->where('user_course_status.user_id', $userId)
                ->where('user_course_status.is_start', 1)
                ->where('user_course_status.is_complete', 0);

                if($limit > 0){
                    $courses = $courses->limit($limit);
                }
                $courses = $courses->get();
        
        return $courses;
    }

    public static function GetUserAllCompletedCourses($userId, $limit=0){

        $courses = Course::join('user_course_status', 'courses.id', '=', 'user_course_status.course_id')
                ->select('courses.*')
                ->where('user_course_status.user_id', $userId)
                ->where('user_course_status.is_complete', 1);

                if($limit > 0){
                    $courses = $courses->limit($limit);
                }
                $courses = $courses->get();
        
        return $courses;
    }

    public static function GetUserAllFavoriteCourses($userId, $limit=0){

        $courses = Course::join('user_favorite_course', 'courses.id', '=', 'user_favorite_course.course_id')
                ->select('courses.*')
                ->where('user_favorite_course.user_id', $userId);

                if($limit > 0){
                    $courses = $courses->limit($limit);
                }
                $courses = $courses->get();
        
        return $courses;
    }

    public static function GetUsersInprogressCourseCount($userId){

        $count = Course::join('user_course_status', 'courses.id', '=', 'user_course_status.course_id')
                ->select('courses.*')
                ->where('user_course_status.user_id', $userId)
                ->where('user_course_status.is_start', 1)
                ->where('user_course_status.is_complete', 0)->count();
        
        return $count;
    }

    public static function GetUsersFavoriteCourseCount($userId){

        $count = Course::join('user_favorite_course', 'courses.id', '=', 'user_favorite_course.course_id')
                ->select('courses.*')
                ->where('user_favorite_course.user_id', $userId)->count();
        
        return $count;
    }

    public static function GetUsersLatestFavoriteCourse($userId){

        $course = Course::join('user_favorite_course', 'courses.id', '=', 'user_favorite_course.course_id')
                ->select('courses.*')
                ->where('user_favorite_course.user_id', $userId)
                ->orderBy('user_favorite_course.id', 'desc')->first();
        
        return $course;
    }

    public static function GetUsersLatestInprogressCourse($userId){

        $course = Course::join('user_course_status', 'courses.id', '=', 'user_course_status.course_id')
                ->select('courses.*')
                ->where('user_course_status.user_id', $userId)
                ->where('user_course_status.is_start', 1)
                ->where('user_course_status.is_complete', 0)
                ->orderBy('user_course_status.id', 'desc')->first();
        
        return $course;
    }
}