<?php
namespace App\Http\Middleware;


use Closure;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Redirect;
use Session;
use URL;
use Request;

class SentinelSalesOfficer
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
        return Redirect::to('login')->with('error', 'You must be logged in!');
        elseif(!(Sentinel::inRole('salesofficier')))
         return Redirect::to('/');
		
        return $next($request);
    }
}
