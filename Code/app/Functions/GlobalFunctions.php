<?php

    use Laravel\Sanctum\PersonalAccessToken;

    function first_function()
    {
        //function logic
    }

    function CheckRoleHasPermission($module, $access)
    {
        //e.g. $module=campus, $access=is_read        
        $hasAccess = 0;
        $permissionArray = Session::get('PermissionArray');

        if(isset($permissionArray[$module][$access]))
        {
            $hasAccess = $permissionArray[$module][$access];
        }

        return $hasAccess;
    }

    function GetSanctumLoggedUser($bearerToken)
    {
        $token = PersonalAccessToken::findToken($bearerToken);
        $loggedUser = $token->tokenable;
        return $loggedUser;
    }

    function GenerateRandomString($strength = 3) {

        $input = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $input_length = strlen($input);    
        $random_string = '';
    
        for($i = 0; $i < $strength; $i++) {    
            $random_character = $input[mt_rand(0, $input_length - 1)];    
            $random_string .= $random_character;    
        }
    
        return strtoupper($random_string);    
    }

    // For add'active' class for activated route nav-item
    function ActiveClass($path, $active = 'active') {
        return call_user_func_array('Request::is', (array)$path) ? $active : '';
    }
  
  // For checking activated route
  function is_active_route($path) {
    return call_user_func_array('Request::is', (array)$path) ? 'true' : 'false';
  }
  
  // For add 'show' class for activated route collapse
  function show_class($path) {
    return call_user_func_array('Request::is', (array)$path) ? 'show' : '';
  }

  function getCurrencyWiseAmount($currency, $amount){
    if($currency == '&#8377;'){
        $amount = $amount * 80;
    }
    return $amount;
  }
  // Ip Detection code 
// PHP code to extract IP 
function getVisIpAddr() {
      
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else {
        return $_SERVER['REMOTE_ADDR'];
    }
}
    
    