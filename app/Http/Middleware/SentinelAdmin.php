<?php
namespace App\Http\Middleware;


use Closure;
// use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Redirect;
use Session;
use URL;
use Request;

class SentinelAdmin
{
    // Admin panel: minutes of inactivity before the admin is signed out.
    const ADMIN_IDLE_MINUTES = 60;

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
        return Redirect::to('cpmin/signin')->with('error', 'You must be logged in!');
        elseif(!(Sentinel::inRole('admin') || Sentinel::inRole('sub-admin')))
         return Redirect::to('/');

        // Sign the admin out after ADMIN_IDLE_MINUTES without activity. The time of
        // the last request is kept in the session; every request refreshes it, and
        // a session that has none (an old login, or one that expired) is signed out.
        $last = Session::get('admin_last_activity');
        if (!$last || (time() - (int) $last) > self::ADMIN_IDLE_MINUTES * 60) {
            Sentinel::logout();
            Session::forget('admin_last_activity');
            return Redirect::to('cpmin/signin')->with('error', 'Your session expired. Please sign in again.');
        }
        Session::put('admin_last_activity', time());

        return $next($request);
    }
}
