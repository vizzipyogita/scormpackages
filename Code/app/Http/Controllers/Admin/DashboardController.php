<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\Organization;
use App\Models\CourseCategory;
use Validator;
use Session;

class DashboardController extends Controller
{
    var $data = array();
    private $CourseModel;
    private $UserModel;
    private $OrganizationModel;

    public function __construct()
    {
        $this->CourseModel = new Course();  
        $this->UserModel = new User();  
        $this->OrganizationModel = new Organization();  
        $this->CourseCategoryModel = new CourseCategory();     
    }

    public function dashboard(Request $request)
    {
        $loggedUserOrganizationId = $request->session()->get('LoggedUserOrganizationId');

        // User Count
        $usersCount = $this->UserModel->GetAllOrganizationUsersCount($loggedUserOrganizationId);
        $organizationCount = $this->OrganizationModel->GetTotalOrganizationCount();
        $courseCount = $this->CourseModel->GetTotalCourseCount();

        $this->data['usersCount'] = $usersCount;	
        $this->data['organizationCount'] = $organizationCount;	
        $this->data['courseCount'] = $courseCount;	
        $this->data['loggedUserOrganizationId'] = $loggedUserOrganizationId;	
        return view ('dashboard', $this->data);
    }

    public function userdashboard(Request $request)
    {
        $loggedUserId = $request->session()->get('LoggedUserId');
        //Get Query Param
        $categoryId = isset($request->category_id) ? $request->category_id : 0;
        $title = isset($request->title) ? $request->title : '';
        $freeTrialDays = 0;
        //Get User Details
        $user = $this->UserModel->GetUserDetails($loggedUserId);
        // Category List
        $courseCategories = $this->CourseCategoryModel->GetAllCategories();
        //Get User Latest favorite course
        $favoriteCourse = $this->CourseModel->GetUsersLatestFavoriteCourse($loggedUserId);
        $favoriteCoursesCount = $this->CourseModel->GetUsersFavoriteCourseCount($loggedUserId);

        //Get User Latest inprogress course
        $inprogressCourse = $this->CourseModel->GetUsersLatestInprogressCourse($loggedUserId);
        $inprogressCoursesCount = $this->CourseModel->GetUsersInprogressCourseCount($loggedUserId);

        //calculate free trial days
        $now = strtotime(date('Y-m-d h:i:s')); // or your date as well
        $your_date = strtotime($user->created_at);
        $datediff = $now - $your_date;

        $freeTrialDays = round($datediff / (60 * 60 * 24));

        $freeTrialDays = $freeTrialDays - 5;

        //Get User subscribed categories count
        $subscribedCategoryCount = $this->CourseCategoryModel->getSubscribedCategoryCount($loggedUserId);

        //get all courses
        $courses = $this->CourseModel->GetAllCourses($categoryId, $title);
        $this->data['pageTitle'] = 'User Dashboard';
        $this->data['loggedUserId'] = $loggedUserId;	
        $this->data['courses'] = $courses;	
        $this->data['courseCategories'] = $courseCategories;	
        $this->data['categoryId'] = $categoryId;	
        $this->data['title'] = $title;
        $this->data['loggedUserId'] = $loggedUserId;
        $this->data['favoriteCourse'] = $favoriteCourse;
        $this->data['inprogressCourse'] = $inprogressCourse;
        $this->data['favoriteCoursesCount'] = $favoriteCoursesCount;
        $this->data['inprogressCoursesCount'] = $inprogressCoursesCount;
        $this->data['user'] = $user;
        $this->data['freeTrialDays'] = abs($freeTrialDays);
        $this->data['subscribedCategoryCount'] = $subscribedCategoryCount;
        

        return view ('user_dashboard',$this->data);
    }
}
