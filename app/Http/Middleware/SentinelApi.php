<?php
namespace App\Http\Middleware;


use Closure;
use Sentinel;
use Redirect;
use Session;
use URL;
use Request;

class SentinelApi
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
		
     $acceptHeader = $request->header('Custom-Security');
     //if ($acceptHeader != 'XWDhiXytfRxppXiwa6XgewAAAoY') {
        // return response()->json([], 400);
    // }
		
        return $next($request);
    }
}
