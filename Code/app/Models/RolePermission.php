<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;
use DB;

class RolePermission extends Model
{
    use HasFactory;
    public $timestamps = false;    
    protected $table = 'role_permissions';
    protected $fillable = [
        'role_id', 
        'permission_id',
        'is_create',
        'is_read',
        'is_update',
        'is_delete',
    ];    

    public function PermissionModule()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
        
    }

    public function users()
    {
        return $this->hasManyThrough(User::class,Role::class);
    }


    public static function GetAllRolePermissions($role_id){        
        $records = RolePermission::where('role_id', $role_id)->get();
        return $records;
    }

    public static function GetRolePermissionDetails($id){        
        $record = RolePermission::find($id);
        return $record;
    }

    public static function SetRolePermission($input, $role_id){
       
        //First delete all records
        $delete = RolePermission::where('role_id', $role_id)->delete(); 

        $permission_ids = $input['permission_ids'];
       
        $data = [];
        foreach($permission_ids as $permission_id){
            $data[] = [
                'role_id' => $role_id,
                'permission_id' => $permission_id,
                'is_create' => isset($input['is_create_'.$permission_id]) ? 1: 0,
                'is_read'=> isset($input['is_read_'.$permission_id]) ? 1: 0,
                'is_update'=> isset($input['is_update_'.$permission_id]) ? 1: 0,
                'is_delete'=> isset($input['is_delete_'.$permission_id]) ? 1: 0,
            ];
        }

        RolePermission::insert($data);

        return true;
    }

    public static function SetAdministratorRolePermission($role_id){
       
        //Get
        $permissions = DB::table('permissions')->get();

        $data = [];
        foreach($permissions as $permission){
            $data[] = [
                'role_id' => $role_id,
                'permission_id' => $permission->id,
                'is_create' => 1,
                'is_read'=> 1,
                'is_update'=> 1,
                'is_delete'=> 1,
            ];
        }

        RolePermission::insert($data);

        return true;
    }


}