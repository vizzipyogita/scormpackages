<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Models\Campus;
use App\Http\Resources\Campus as CampusResource;
use Laravel\Sanctum\PersonalAccessToken;
   
class CampusController extends BaseController
{
    private $CampusModel;
    public function __construct()
    {
        $this->CampusModel = new Campus();
    }


    public function index(Request $request)
    {
        $loggedUser = GetSanctumLoggedUser($request->bearerToken());
        if (is_null($loggedUser)) {
            return $this->sendError('User does not exist.');
        }

        $campuses = $this->CampusModel->GetAllCampuses($loggedUser->id);
        return $this->sendResponse(CampusResource::collection($campuses), 'Campuses fetched.');
    }
    
    public function store(Request $request)
    {
        //Authenticate Logged User
        $loggedUser = GetSanctumLoggedUser($request->bearerToken());
        if (is_null($loggedUser)) {
            return $this->sendError('User does not exist.');
        }

        //Validate Input
        $input = $request->all();
        $validator = $this->CampusModel->ValidateStoreCampus($input);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }

        //Save Campuses
        $campusNames = $request->name;
        $this->CampusModel->SaveCampus($campusNames, $loggedUser->id);        

        //Return Output
        return $this->sendResponse([], 'Campus created.');
    }
   
    public function show($id)
    {
        $campus = $this->CampusModel->GetCampusDetails($id);
        if (is_null($campus)) {
            return $this->sendError('Campus does not exist.');
        }
        return $this->sendResponse(new CampusResource($campus), 'Campus fetched.');
    }
    
    public function updateCampus(Request $request)
    {
        $campus = $this->CampusModel->GetCampusDetails($request->id);
        if (is_null($campus)) {
            return $this->sendError('Campus does not exist.');
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }

        $campus->name = $input['name'];
        $campus->save();
        
        return $this->sendResponse(new CampusResource($campus), 'Campus updated.');
    }
   
    public function destroy(Campus $campus)
    {
        $campus->delete();
        return $this->sendResponse([], 'Campus deleted.');
    }
}