<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use Session;

class AuthWeb
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,  $guard = null)
    {
        if(Auth::check()) {
            if(Auth::user()->status == '1'){
                return $next($request);
            }else{
                Auth::logout();
                Session::flash('message', "message_login_status");
                return redirect()->to('/login')->withInput($request->input());
            }
        } else {
            return redirect('/login');
        }
    }
}
