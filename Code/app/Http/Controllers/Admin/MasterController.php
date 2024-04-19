<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\State;
use App\Models\Country;
use Session;
use DB;

class MasterController extends Controller
{
    public function __construct()
    {
    }
    

    public function states(Request $request)
    {
		try {            
            //Get Input
            $countryId = isset($request->countryId) ? $request->countryId : 0;

            //Find Country
            $country = Country::GetCountryDetails($countryId);
            if (is_null($country)) {
                $response = array('status'=>'error', 'message'=>'Country does not exist.');
                return response()->json($response);
            }       
            
            //Get all states by country Id
            $states = $country->states;           
           
        } catch (Exception $e) {
			if($request->ajax())
			{
				$response = array('status'=>'error', 'message'=>$e->getMessage());
				return response()->json($response);
			}
        }		
		
		$response = array('status'=>'success', 'message'=>'success.', 'states'=>$states);
		return response()->json($response);
    }


}
