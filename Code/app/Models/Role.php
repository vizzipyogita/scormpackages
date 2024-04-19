<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class Role extends Model
{
    use HasFactory;
    public $timestamps = true;    
    protected $table = 'role';
    protected $fillable = [
        'organization_id', 
        'name',
        'is_primary_role',
        'created_at',
        'updated_at',
        'created_by'
    ];    

   

    public function users()
    {
        return $this->hasMany(User::class);
    
    }

    public function permissions()
    {
        return $this->hasManyThrough(RolePermission::class,Role::class, 'id');
    }

  


    public static function ValidateStore($input, $id=0, $organization_id=0){

        if($id==0) //save
        {
            $validator = Validator::make($input, [
                'name' => 'required|unique:role,name,' . $input['name'] . ',id,organization_id,' . $organization_id,
            ]);
        }
        else
        { 
            //update
            $validator = Validator::make($input, [               
                'name' => 'required|unique:role,name,' . $id . ',id,organization_id,'.$organization_id,             
            ]);
        }       

        return $validator;
    }
    

    public static function SaveRole($input, $isDefault=0){
        $role = new Role;

        if($isDefault==1)
        {
            //Save Role Info
            $role->organization_id = $input['organization_id'];
            $role->name = 'Administrator';
            $role->is_primary_role = 1;
            $role->created_by = 0;
            $role->save();
        }
        else{
            //Save Role Info
            $role->organization_id = $input['organization_id'];
            $role->name = $input['name'];
            $role->is_primary_role = isset($input['is_primary_role']) ? $input['is_primary_role'] : 0;
            $role->created_by = isset($input['created_by']) ? $input['created_by'] : 0;
            $role->save();
        }
       

        return $role;
    }

    public static function UpdateRole($input, $id)
    {
        //Find Role
        $role = Role::find($id);        

        //Save Role Info
        $role->name = $input['name'];
        $role->save();

        return $role;
    }

    public static function GetAllRoles($organization_id){        
        $records = Role::where('organization_id', $organization_id)->orderby('created_at', 'DESC')->get();
        return $records;
    }

    public static function GetRoleDetails($id){        
        $record = Role::find($id);
        return $record;
    }


    public static function GetTotalRoleCount($organization_id){        
        $count = Role::where('organization_id', $organization_id)->count();
        return $count;
    }

    public static function GetRoleCountByFilter($search_value,$organization_id){        
        $count = Role::where('organization_id', $organization_id)->where('name', 'like', '%' .$search_value . '%')->count();
        return $count;
    }

    public static function GetRolesForDT($organization_id, $column_name, $column_sortorder,$search_value, $start, $row_perpage)
    {        
        $records = Role::orderBy($column_name,$column_sortorder)
                                ->where('role.name', 'like', '%' .$search_value . '%')
                                ->where('organization_id', $organization_id)
                                ->select('role.*')
                                ->skip($start)
                                ->take($row_perpage)
                                ->get();
        return $records;
    }

    public static function DeleteRole($id){        
        Role::where('id', $id)->firstorfail()->delete();
        return true;
    }

}