<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class Permission extends Model
{
    use HasFactory;
    public $timestamps = true;    
    protected $table = 'permissions';
    protected $fillable = [
        'name', 
        'display_name',
        'sortorder',
        'is_section',
        'parent_id',
    ];    


    public static function GetAllPermissions(){        
        $records = Permission::orderby('sortorder', 'ASC')->get();
        return $records;
    }

    public static function GetPermissionDetails($id){        
        $record = Permission::find($id);
        return $record;
    }


}