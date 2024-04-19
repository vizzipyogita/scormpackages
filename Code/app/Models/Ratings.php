<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;
use ZipArchive;
use File;

class Ratings extends Model
{
    use HasFactory;
    public $timestamps = true;    
    protected $table = 'users_course_ratings';
    protected $fillable = [
        'user_id', 
        'course_id',
        'rating_points',
        'comment',
        'is_active'
    ];   
  
    public static function GetAllCourseRatings($courseId, $limit=0){        
        $ratings = Ratings::where('course_id', $courseId)
                    ->where('comment', '!=', '')
                    ->orderby('created_at', 'DESC');
                    if($limit > 0){
                        $ratings = $ratings->limit($limit);
                    }
                    $ratings = $ratings->get();

        return $ratings;
    }

    public static function SaveRatings($input, $courseId=0, $userId=0){
        $rating = new Ratings;

        $rating->user_id = $userId;
        $rating->course_id = $courseId;
        $rating->rating_points = $input['rating_points'];
        $rating->comment  = $input['comment'];
        $rating->is_active  = isset($input['is_active']) ? $input['is_active'] : 1;

        $rating->save();

        return $rating;
    }

    public static function UpdateRating($input, $courseId=0, $userId=0)
    {
        //Find rating
        $rating = Ratings::where('user_id', $userId)->where('course_id', $courseId)->first();

        if(isset($input['rating_points']) && $input['rating_points']!="")
            $rating->rating_points = $input['rating_points'];

        if(isset($input['comment']) && $input['comment']!="")
            $rating->comment = $input['comment'];
        
        if(isset($input['is_active']) && $input['is_active']!="")
            $rating->is_active = $input['is_active'];

        //Save Rating Info
        $rating->save();

        return $rating;
    }

    public static function UpdateRatingStatus($input, $id=0)
    {
        //Find rating
        $rating = Ratings::find($id);

        if(isset($input['is_active']) && $input['is_active']!="")
            $rating->is_active = $input['is_active'];

        //Save Rating Info
        $rating->save();

        return $rating;
    }


    public static function GetRatingDetails($id){        
        $rating = Ratings::find($id);
        return $rating;
    }

    public static function GetRatingDetailsByCourseIdUserId($courseId, $userId){        
        $rating = Ratings::where('user_id', $userId)->where('course_id', $courseId)->first();
        return $rating;
    }

    public static function GetTotalCourseRatingCount($courseId){        
        $count = Ratings::where('course_id', $courseId)->count();
        return $count;
    }

    public static function GetRatingDetailsByUserId($userId, $courseId){        
        $rating = Ratings::where('user_id', $userId)->where('course_id', $courseId)->first();
        return $rating;
    }

    public static function GetTotalRatingsCount(){        
        $count = Ratings::where('comment', '!=', '')->count();
        return $count;
    }

    public static function GetTotalActiveRatingsCount($courseId=0){        
        $count = Ratings::where('comment', '!=', '')->where('is_active', 1);
                if($courseId > 0){
                    $count = $count->where('course_id', $courseId);
                }
                $count = $count->count();
        return $count;
    }

    public static function GetCountRatingsByFilter($search_value){        
        $count = Ratings::where('comment', 'like', '%' .$search_value . '%')->count();
        return $count;
    }

    public static function GetRatingsForDT($column_name, $column_sortorder,$search_value, $start, $row_perpage)
    {        
        $records = Ratings::orderBy($column_name,$column_sortorder)
                                ->where('users_course_ratings.comment', 'like', '%' .$search_value . '%')
                                ->select('users_course_ratings.*')
                                ->skip($start)
                                ->take($row_perpage)
                                ->get();
        return $records;
    }

}