<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Models\User;
use App\Http\Resources\User as UserResource;
   
class UserController extends BaseController
{
    private $UserModel;
    public function __construct()
    {
        $this->UserModel = new User();
    }

    public function userDetails(Request $request)
    {
        //Get Sanctum user
        $loggedUser = GetSanctumLoggedUser($request->bearerToken());
        if(is_null($loggedUser)) {
            return $this->sendError('User does not exist.');
        }

        $user = $this->UserModel->GetUserDetails($request->id);
        if (is_null($user)) {
            return $this->sendError('User does not exist.');
        }

        //Check Logged user and Sanctum user are same
        if ($user->id!=$loggedUser->id) {
            return $this->sendError('Logged user and passed user is different.');
        }

        //Get Current bearerToken
        $loggedUser['token'] = $request->bearerToken();
        $loggedUser['photo_path'] = $this->UserModel->GetUserImage($user->id, $user->image_name);
        return $this->sendResponse(new UserResource($loggedUser), 'User fetched.');
    }

    public function updateUser(Request $request)
    {
        $loggedUser = GetSanctumLoggedUser($request->bearerToken());
        if(is_null($loggedUser)) {
            return $this->sendError('User does not exist.');
        }

        $user = $this->UserModel->GetUserDetails($request->id);
        if (is_null($user)) {
            return $this->sendError('User does not exist.');
        }

        //Check Logged user and Sanctum user are same
        if ($user->id!=$loggedUser->id) {
            return $this->sendError('Logged user and passed user is different.');
        }

        if(isset($request->image_name))
        {
            //Upload Photo
            $this->UserModel->UploadUserImage($request, $loggedUser->id);     
            return $this->sendResponse([], 'User photo uploaded.');  
        }
        else{
            //Update User
            $input = $request->all();
            $updatedUser = $this->UserModel->UpdateUser($input, $loggedUser->id);
        }
       
 
        return $this->sendResponse([], 'User updated.');
    }


}