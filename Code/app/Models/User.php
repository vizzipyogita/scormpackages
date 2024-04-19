<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Validator;
use File;
use Session;
// sanctum
use Laravel\Sanctum\HasApiTokens;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Validation\Rule; 
use App\Models\Country;
use App\Models\SubscriptionPlans;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'firstname',
        'lastname',
        'email',
        'password',
        'normal_password',
        'user_type',
        'country_id',
        'mobile_number',
        'image_name',
        'role_id',
        'sos_pin',
        'is_primary_user',
        'is_guest',
        'is_subscription',
        'subscription_end_date',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public static function SetSession($user, $previousLoggedUserId=0){        

        //Get Logged User Image path
        $loggedUserPhotoPath = User::GetUserImage($user->id, $user->image_name);

        //Get All User Permissions
        $role = $user->Role;
        $role_name = $role ? $role->name : '';
        $countryId = 228; //$user->country_id ? $user->country_id : '228';
        $permissions = $role ? $role->permissions : [];
        $permission_array = [];
        $currency = '$';
        $currencyCode = 'USD';

        // Get Currency Code and country code
        $ip = getVisIpAddr();
        $ipdat = @json_decode(file_get_contents(
            "http://www.geoplugin.net/json.gp?ip=" . $ip));

        // echo "<pre>";print_r($ipdat);exit;
        if($ipdat){
            $countryCode = isset($ipdat->geoplugin_countryCode) ? $ipdat->geoplugin_countryCode : 'USD';
            if($countryCode == ''){
                $currencyCode = 'USD';
            }
            
            if(isset($ipdat->geoplugin_currencySymbol)){
                $currency = isset($ipdat->geoplugin_currencySymbol) ? $ipdat->geoplugin_currencySymbol : '$';
            } 
            //get country by code
            $countryDetails = Country::GetCountryDetailsByCountryCode($countryCode);
            if($countryDetails){
                $countryId = $countryDetails->id;
            }
            $plans = SubscriptionPlans::GetSubscriptionPlansByCountryId($countryId);
            if(count($plans)){
                //Continue with existing country
            }else{
                $countryId = 228;
                $currency = '$';
                $currencyCode = 'USD';
            }           
        }
         
        if($countryId == 101){
            $currency = '&#8377;';
        }
        if(count($permissions)>0)
        {
            foreach($permissions as $permission)
            {
                $permission_array[$permission->PermissionModule->name] = [
                    'role_name' => $role_name,
                    'Permission_module' => $permission->PermissionModule->name,
                    'is_read' => $permission->is_read,
                    'is_create'=> $permission->is_create,
                    'is_update'=> $permission->is_update,
                    'is_delete'=> $permission->is_delete,
                ];
            }
        }
        
        //echo '<pre>';
       // print_r($permission_array['systemusers']['is_update']);exit;
        
        session(['IsUserLoggedIn'=>1 ,'LoggedUserName' => $user->firstname." ".$user->lastname,
        'LoggedUserId' => $user->id,'LoggedUserType' => $user->user_type,'LoggedUserEmail' => $user->email, 'LoggedUserPhotoPath' => $loggedUserPhotoPath, 
        'LoggedUserOrganizationId' => $user->organization_id, 'LoggedUserOrganizationName' => $user->Organization->organization_name, 
        'LoggedOrgUserId'=>$previousLoggedUserId, 'PermissionArray'=>$permission_array, 'isUserLogin'=>0, 'loggedUserCountryId'=>$countryId, 
        'currency'=>$currency, 'currencyCode', $currencyCode]);
    }

    public function Country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function Role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function permissions()
    {
        return $this->hasManyThrough(RolePermission::class,Role::class);
    }

    public function Organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }


    public static function GenerateToken($user)
    {
        return $user->createToken($user->id.'SecurityApp')->plainTextToken; 
    }

    public static function GetUserDetails($id){        
        $user = User::find($id);
        return $user;
    }

    public static function ValidateStore($input, $id=0, $organization_id=0){

        $validator = Validator::make($input, [
            "firstname"    => "required",
            "lastname"  => "required", 
            'email' => ['required', 'string', 'email', 'max:50',Rule::unique('users')->where(function ($query) use ($organization_id) {
                return $query->where('organization_id', $organization_id);
            })],       
        ]);

        return $validator;
    }

    public static function SaveUser($input, $user_type='A', $organization_id=0)
    {
        $user = new User;

        //Save User Info
        $user->organization_id = $organization_id;
        $user->firstname = $input['firstname'];
        $user->lastname= $input['lastname'];
        $user->email= $input['email'];
        $user->user_type= $user_type;
        $user->normal_password = isset($input['password']) ? $input['password'] : '123456';
        $user->password = isset($input['password']) ? bcrypt($input['password']) : bcrypt('123456'); //bcrypt($input['password']); 
        $user->country_id = isset($input['country_id']) ? $input['country_id'] : '228';
        $user->state_id = isset($input['state_id']) ? $input['state_id'] : '0';
        $user->address = isset($input['address']) ? $input['address'] : '';
        $user->city = isset($input['city']) ? $input['city'] : '';
        $user->zip = isset($input['zip']) ? $input['zip'] : '';

        if(isset($input['mobile_number']) && $input['mobile_number']!="")
            $user->mobile_number = $input['mobile_number'];

        // if(isset($input['country_id']) && $input['country_id']!="")
        //     $user->country_id = $input['country_id'];  

        if(isset($input['role_id']) && $input['role_id']!="")
            $user->role_id = $input['role_id']; 
        
        if(isset($input['is_guest']) && $input['is_guest']!="")
            $user->is_guest = $input['is_guest']; 


        if($user_type == 'A'){
            $user->role_id = 1; 
        }else if($user_type == 'T'){
            $user->role_id = 2; 
        }else{
            $user->role_id = 3; 
        }

        $user->save();

        return $user;
    }

    public static function UpdateUser($input, $id)
    {
        //Find User
        $user = User::find($id);

        if(isset($input['firstname']) && $input['firstname']!="")
            $user->firstname = $input['firstname'];

        if(isset($input['lastname']) && $input['lastname']!="")
            $user->lastname = $input['lastname'];

        if(isset($input['country_id']) && $input['country_id']!="")
            $user->country_id = $input['country_id'];  

        if(isset($input['mobile_number']) && $input['mobile_number']!="")
            $user->mobile_number = $input['mobile_number'];

        if(isset($input['sos_pin']) && $input['sos_pin']!="")
            $user->sos_pin = $input['sos_pin'];

        if(isset($input['role_id']) && $input['role_id']!="")
            $user->role_id = $input['role_id']; 

        if(isset($input['user_type']) && $input['user_type']!="")
            $user->user_type = $input['user_type']; 

        if(isset($input['is_subscription']) && $input['is_subscription']!="")
            $user->is_subscription = $input['is_subscription']; 

        if(isset($input['subscription_end_date']) && $input['subscription_end_date']!="")
            $user->subscription_end_date = $input['subscription_end_date']; 

        if(isset($input['country_id']) && $input['country_id']!="")
            $user->country_id = $input['country_id'];

        if(isset($input['state_id']) && $input['state_id']!="")
            $user->state_id = $input['state_id'];

        if(isset($input['address']) && $input['address']!="")
            $user->address = $input['address'];

        if(isset($input['city']) && $input['city']!="")
            $user->city = $input['city'];

        if(isset($input['zip']) && $input['zip']!="")
            $user->zip = $input['zip'];


        //Save User Info
        $user->save();

        return $user;
    }
    
    public static function SaveSystemUserWhileCreatingORG($organization_id, $fullname, $email, $password='')
    {
        $user = new User;

        //Save User Info
        $name = explode(" ", $fullname);
        $user->organization_id = $organization_id;
        $user->firstname= $name[0];
        $user->lastname= isset($name[1]) ? $name[1] : '';
        $user->email= $email;
        $user->user_type= 'A';
        $user->password= isset($password) ? bcrypt($password) : bcrypt('123456');
        $user->normal_password = isset($password) ? $password : '123456';
        $user->is_primary_user = 1;
        
        $user->save();

        return $user;
    }

    public static function GetTotalOrgUsersCount($organization_id){        
        $count = User::where('organization_id', $organization_id)->where('user_type','!=', 'A')->count();
        return $count;
    }

    public static function GetTotalOrgSystemUsersCount($organization_id){        
        $count = User::where('organization_id', $organization_id)->where('user_type', 'A')->count();
        return $count;
    }

    public static function GetOrgCountSystemUsersByFilter($search_value,$organization_id){        
        $count = User::where('organization_id', $organization_id)->where('firstname', 'like', '%' .$search_value . '%')->count();
        return $count;
    }

    public static function GetOrgSystemUsersForDT($organization_id, $column_name, $column_sortorder,$search_value, $start, $row_perpage)
    {        
        $records = User::orderBy($column_name,$column_sortorder)
                                ->where('users.firstname', 'like', '%' .$search_value . '%')
                                ->where('organization_id', $organization_id)
                                ->select('users.*')
                                ->skip($start)
                                ->take($row_perpage)
                                ->get();
        return $records;
    }

    public static function DeleteOrgSystemUser($id){        
        User::where('id', $id)->firstorfail()->delete();
        return true;
    }

    public static function UploadUserImage($input, $id){       
        
        //Find User
        $user = User::find($id);

        //Delete old image 
        if(isset($user->image_name)){
            if($user->image_name!="")
            {
                $filename = public_path('/uploads/users/'.$id.'/'.$user->image_name);
                if(File::exists($filename)) 
                    File::delete($filename);                
            }
        }
               

        //Upload Image
		$image = $input->file('image_name');
		$destinationPath = public_path('/uploads/users/'.$id);
		$image_name = rand().'.'.$image->getClientOriginalExtension(); 
		$image->move($destinationPath, $image_name);
		
		//Update Image name
        $user->image_name = $image_name; 
        $user->save();
	
        return $user;
    }

    public static function GetUserImage($id, $image_name=""){       
       
        if($image_name!="")
            $filepath = '/uploads/users/'.$id.'/'.$image_name;
        else 
            $filepath = '/assets/img/user.png';;

        return $filepath;
    }

    public static function FindOrgPrimarySystemUser($organization_id){        
        $user = User::where('organization_id', $organization_id)->where('is_primary_user', 1)->first();
        return $user;
    }

    public static function UpdateUserPassword($input, $userId){        
        //Find User
        $user = User::find($userId);

        $user->normal_password = isset($input['password']) ? $input['password'] : '123456';
        $user->password = isset($input['password']) ? bcrypt($input['password']) : bcrypt('123456'); //bcrypt($input['password']);

        //Save User Info
        $user->save();

        return $user;
    }

    public static function UploadImportUsersFile($input){       
            
        //Upload file
		$file = $input->file('file_name');
		$destinationPath = public_path('/uploads/users');
		$file_name = rand().'.'.$file->getClientOriginalExtension(); 
		$file->move($destinationPath, $file_name);
		
        Session::put('usersImportFileName', $file_name);

        return true;
    }

    public static function GetAllImportUsersList($organizationId){
        $fileName = Session::get('usersImportFileName');
        $destinationPath = public_path('/uploads/users/');
        $destFilePath = $destinationPath . $fileName;

        $inputFileType = IOFactory::identify($destFilePath);
		$reader = IOFactory::createReader($inputFileType);
		$spreadsheet = $reader->load($destFilePath);
		$spreadsheet->setActiveSheetIndex(0);

		$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
		
		$arrUserList = array();
        $arrUsers = array();
        $errorCount = 0;
        if(count($sheetData)) {
            
            foreach($sheetData as $index=>$row) {

                if($index==1) continue;	//skip first row having titles
                
                $firstName = array_key_exists('A', $row) ? $row['A'] : '';
                $lastName = array_key_exists('B', $row) ? $row['B'] : '';
                $email = array_key_exists('C', $row) ? $row['C'] : '';
                $mobile = array_key_exists('D', $row) ? $row['D'] : '';
                $errorMessage = '';

                if($firstName !='' && $lastName !='' && $email !=''){
                    //Check Duplicate email
                    $input['firstname'] = $firstName;
                    $input['lastname'] = $lastName;
                    $input['email'] = $email;

                    $validator = Validator::make($input, [
                        "firstname"    => "required",
                        "lastname"  => "required", 
                        'email' => ['required', 'string', 'email', 'max:50',Rule::unique('users')->where(function ($query) use ($organizationId) {
                            return $query->where('organization_id', $organizationId);
                        })],       
                    ]);
                    if($validator->fails()){
                        $errorMessage = 'Duplicate Email';
                        $errorCount = $errorCount+1;
                    } 

                    $arrUser = array();
                    $arrUser['index'] = $index;
                    $arrUser['errorMessage'] = $errorMessage;
                    $arrUser['firstname'] = $firstName;
                    $arrUser['lastname'] = $lastName;
                    $arrUser['email'] = $email;
                    $arrUser['mobile'] = $mobile;

                    $arrUsers[] = $arrUser;
                }
            }
            
        }

        $arrUserList['errorCount'] = $errorCount;
        $arrUserList['arrUsers'] = $arrUsers;

        return $arrUserList;
    }

    public static function GetAllOrganizationUsersCount($organizationId){        
        $count = User::where('organization_id', $organizationId)->count();
        return $count;
    }
}