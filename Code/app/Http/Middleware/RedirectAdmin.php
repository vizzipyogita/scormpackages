<?php

namespace App\Http\Middleware;

use Closure;

class RedirectAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->session()->has('is_logged_in')){
            return $next($request);
        }
        return redirect('/admin/login')->with('error_message',"Please login for the access");
    }
}
