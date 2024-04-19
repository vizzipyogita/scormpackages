<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\SubscriptionPlans;
use Validator;
use ZipArchive;

class CourseCategory extends Model
{
    use HasFactory;
    public $timestamps = true;    
    protected $table = 'course_categories';
    protected $fillable = [
        'title'
    ];   
    
    public static function GetAllCategories($limit=0){        
        $categories = CourseCategory::where('title', '<>', '');
                if($limit > 0){
                    $categories = $categories->limit($limit);
                }
        
            $categories = $categories->orderby('id', 'ASC')->get();
        return $categories;
    }

    public static function SaveCategogry($input, $id=0){
        $category = new CourseCategory;

        if($id>0)
            $category = CourseCategory::find($id);

        $category->title = $input['title'];
        
        $category->save();

        return $category;
    }


    public static function GetCategoryDetails($id){        
        $category = CourseCategory::find($id);
        return $category;
    }

    public static function GetTotalCategoryCount(){        
        $count = CourseCategory::where('title', '<>', '')->count();
        return $count;
    }

    public static function GetCategoryCountByFilter($searchValue){        
        $count = CourseCategory::where('title', 'like', '%' .$searchValue . '%')->count();
        return $count;
    }

    public static function GetCategoryForDT($columnName, $columnSortOrder,$searchValue,$start, $rowperpage)
    {        
        $records = CourseCategory::orderBy($columnName,$columnSortOrder)
                                ->where('course_categories.title', 'like', '%' .$searchValue . '%')
                                ->select('course_categories.*')
                                ->skip($start)
                                ->take($rowperpage)
                                ->get();
        return $records;
    }

    public function getCategoryCourses($title='', $limit=0)
    {
        $courses = Course::where('category_id', $this->id)->where('is_active', 1);
        if($title !=''){
            $courses = $courses->where('courses.title', 'like', '%' .$title . '%');
        }
        if($limit > 0){
            $courses = $courses->limit($limit);
        }
        
        $courses = $courses->orderBy('sortorder', 'asc')->get();
        return $courses;
    }

    public function checkIsSubscribedCategory($categoryId, $userId){
        $isScubscribedCategory = 0;
        $record = \DB::table('user_subscribed_categories')->where('category_id', $categoryId)->where('user_id', $userId)->first();
        if($record){
            $isScubscribedCategory = 1;
        }

        return $isScubscribedCategory;
    }

    public function GetAllUserSubscribedCategories($userId){
        $categories = \DB::table('user_subscribed_categories')->where('user_id', $userId)->get();
    
        return $categories;
    }

    public function getSubscribedCategoryCount($userId){
        $count = \DB::table('user_subscribed_categories')->where('user_id', $userId)->count();
    
        return $count;
    }

    public static function GetSubscriptionPlansByCountryAndCategoryId($countryId, $categoryId){        
        $plans = SubscriptionPlans::where('country_id', $countryId)->where('category_id', $categoryId)->first();

        return $plans;
    }

    public static function GetSubscriptionPlanForAllByCountry($countryId){        
        $plans = SubscriptionPlans::where('country_id', $countryId)->where('is_all_category', 1)->first();

        return $plans;
    }
}