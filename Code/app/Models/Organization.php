<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class Organization extends Model
{
    use HasFactory;
    public $timestamps = true;    
    protected $table = 'organizations';
    protected $fillable = [
        'organization_code', 
        'organization_name',
        'email',
        'address',
        'city',
        'country_id',
        'state_id',
        'zipcode',
        'phone_number',
        'is_active',
        'contact_person_name',
        'contact_person_email',
    ];    

    public static function GetAllOrganizations(){        
        $organizations = Organization::where('organization_id', '<>', 0)->orderby('created_at', 'DESC')->get();
        return $organizations;
    }

    public static function GetAllOrganizationsWithSuperSchool(){        
        $organizations = Organization::orderby('created_at', 'DESC')->get();
        return $organizations;
    }

    public static function ValidateStoreOrganization($input, $id=0){
        $validator = Validator::make($input, [
            "organization_name"    => "required",
            "organization_code"  => "required",
            'email' => 'required|email|max:50|unique:organizations,email,'.$id,
            "contact_person_name"  => "required",
            "contact_person_email"  => 'required|email|max:50|unique:organizations,contact_person_email,'.$id,
        ]);

        return $validator;
    }

    public static function SaveOrganization($input, $id=0){
        $organization = new Organization;

        if($id>0)
            $organization = Organization::find($id);
       
        $organization->organization_id = 1;
        $organization->organization_name = $input['organization_name'];
        $organization->organization_code = $input['organization_code'];
        $organization->email = $input['email'];
        $organization->address = isset($input['address']) ? $input['address'] : '';
        $organization->city = isset($input['city']) ? $input['city'] : '';
        $organization->country_id  = isset($input['country_id']) ? $input['country_id'] : 0;
        $organization->state_id  = isset($input['state_id']) ? $input['state_id'] : 0;
        $organization->zipcode  = isset($input['zipcode']) ? $input['zipcode'] : '';
        $organization->phone_number  = isset($input['phone_number']) ? $input['phone_number'] : '';
        $organization->is_active  = isset($input['is_active']) ? $input['is_active'] : 1;
        $organization->contact_person_name  = $input['contact_person_name'];
        $organization->contact_person_email  = $input['contact_person_email'];       
        $organization->save();

       
        return $organization;
    }


    public static function GetOrganizationDetails($id){        
        $organization = Organization::find($id);
        return $organization;
    }

    public static function GetTotalOrganizationCount(){        
        $count = Organization::where('organization_id', '<>', 0)->count();
        return $count;
    }

    public static function GetOrganizationCountByFilter($searchValue){        
        $count = Organization::where('organization_id', '<>', 0)->where('organization_name', 'like', '%' .$searchValue . '%')->count();
        return $count;
    }

    public static function GetOrganizationsForDT($columnName, $columnSortOrder,$searchValue,$start, $rowperpage)
    {        
        $records = Organization::orderBy($columnName,$columnSortOrder)
                                ->where('organizations.organization_name', 'like', '%' .$searchValue . '%')
                                ->where('organization_id', '<>', 0)
                                ->select('organizations.*')
                                ->skip($start)
                                ->take($rowperpage)
                                ->get();
        return $records;
    }

    public static function SaveOrganizationSettings($input, $organizationId){
        \DB::table('organization_settings')->insert(array('organization_id'=>$organizationId, 'user_license'=>$input['user_license']));
    }

    public static function UpdateOrganizationSettings($input, $organizationId){
        \DB::table('organization_settings')->where('organization_id', $organizationId)->update(array('user_license'=>$input['user_license']));
    }

    public static function GetOrganizationSettings($organizationId){
        $settings = \DB::table('organization_settings')->where('organization_id', $organizationId)->first();

        return $settings;
    }

    public static function UpdateOrganizationSubscriptionSettings($input, $organizationId){
        \DB::table('organization_settings')->where('organization_id', $organizationId)
            ->update($input);
    }

}