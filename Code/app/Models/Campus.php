<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class Campus extends Model
{
    use HasFactory;
    public $timestamps = true;    
    protected $table = 'campus';
    protected $fillable = [
        'user_id', 
        'name',
        'code',
        'code_expire_date'
    ];    


    public static function ValidateStoreCampus($input){
        $validator = Validator::make($input, [
            "name"    => "required|array|min:1",
            "name.*"  => "required|string|distinct|min:1",
        ]);

        return $validator;
    }

    public static function SaveCampus($campusNames, $loggedUserId){
        $data = [];
        foreach($campusNames as $name) {
            $data[] = [
                'user_id' => $loggedUserId,
                'name' => $name,
                'code' => GenerateRandomString(3).'-'.GenerateRandomString(3),
                'code_expire_date'=> date('Y-m-d H:i:s', strtotime("+3 days")),
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ];
        }

        Campus::insert($data);
    }

    public static function GetAllCampuses($loggedUserId){        
        $campuses = Campus::where('user_id', $loggedUserId)->orderby('created_at', 'DESC')->get();
        return $campuses;
    }

    public static function GetCampusDetails($id){        
        $campus = Campus::find($id);
        return $campus;
    }

    public static function GetLoggedUserCampusDetails($id, $user_id){        
        $campus = Campus::where('id', $id)->where('user_id', $user_id)->first();
        return $campus;
    }

    public static function GetTotalCampusCount($user_id){        
        $count = Campus::where('user_id', $user_id)->count();
        return $count;
    }

    public static function GetCampusCountByFilter($search_value,$user_id){        
        $count = Campus::where('user_id', $user_id)->where('name', 'like', '%' .$search_value . '%')->count();
        return $count;
    }

    public static function GetCampusForDT($user_id, $column_name, $column_sortorder,$search_value, $start, $row_perpage)
    {        
        $records = Campus::orderBy($column_name,$column_sortorder)
                                ->where('campus.name', 'like', '%' .$search_value . '%')
                                ->where('user_id', $user_id)
                                ->select('campus.*')
                                ->skip($start)
                                ->take($row_perpage)
                                ->get();
        return $records;
    }

    public static function DeleteCampus($id){        
        Campus::where('id', $id)->firstorfail()->delete();
        return true;
    }

}