<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        $middleware = $request->route()->middleware(); 

        if (in_array("auth:user", $middleware)){
            return route('userlogin');
        } else
        {
            return route('login');
        }
        
        // if ($request->is('api/*')) {
        //     return route('unauthorized');
        // }
        
        // if (! $request->expectsJson()) {
        //     return route('userlogin');
        // }
    }
}
