<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Redirect;
use Session;
use URL;
use Request;
class SentinelUser
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
        if(!Sentinel::check())
        return Redirect::to('/signin')->with('error', 'You must be logged in!');
        elseif(Sentinel::inRole('user')!=1)
		return Redirect::to('/signin')->with('error', 'You must be logged in!');
        return $next($request);
   }
}
