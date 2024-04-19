<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use App\Http\Resources\User as UserResource;

   
class AuthController extends BaseController
{
    private $UserModel;
    public function __construct()
    {
        $this->UserModel = new User();
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|max:15',
            'lastname' => 'required|max:15',
            'email' => 'required|email|max:50|unique:users,email',
            'password' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());       
        }
   
        //Save into DB
        $input = $request->all();
        $user = $this->UserModel->SaveUser($input, 'A'); //A for System User

        //Return Array Response
        $user['token'] =  $this->UserModel->GenerateToken($user);//$user->createToken('MyAuthApp')->plainTextToken; 
       
        return $this->sendResponse(new UserResource($user), 'User registered.');
    }

    public function staffregister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|max:15',
            'lastname' => 'required|max:15',
            'email' => 'required|email|max:50|unique:users,email',
            'password' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());       
        }
   
        //Save into DB
        $input = $request->all();
        $user = $this->UserModel->SaveUser($input, 'T'); //A for System User

        //Return Array Response
        $user['token'] =  $this->UserModel->GenerateToken($user);//$user->createToken('MyAuthApp')->plainTextToken; 
       
        return $this->sendResponse(new UserResource($user), 'User registered.');
    }

    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 

            //Delete old Tokens
            $user->tokens()->delete();

            //Generate new tokens
            $user['token'] = $this->UserModel->GenerateToken($user);//$user->createToken('MyAuthApp')->plainTextToken; 
       
            return $this->sendResponse(new UserResource($user), 'User logged in.');
            //return $this->sendResponse($success, 'User signed in');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }

    public function refreshToken(Request $request)
    {
        //Check user is exists in DB
        $user = auth()->user();
        if (is_null($user)) {
            return $this->sendError('User does not exist.');
        }

        //Delete old Tokens
        $user->tokens()->delete();

        //Generate new tokens
        $user['token'] = $this->UserModel->GenerateToken($user);//$user->createToken('MyAuthApp')->plainTextToken; 

        return $this->sendResponse(new UserResource($user), 'User with new fresh Token is fetched.');

    }


    public function logout(Request $request)
    {
        auth()->user()->currentAccessToken()->delete();
        return $this->sendResponse(null, 'User logged out.');
        
    }

    
   
}