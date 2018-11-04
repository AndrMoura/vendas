<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminMiddleware
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
        //dd( Auth::user()->role);
        //dd(Auth::check());
        if(Auth::check() && Auth::user()->role == "admin" ){
            return $next($request);
        }

        return redirect('/login');
    }
}