<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Redirect;
use App\UserService;
class SentinelSeller
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
        
         if(!Sentinel::check()){
			
            return Redirect::route('seller.landing');}
			elseif(Sentinel::check())
           {
			   
			   
			   $this->userId = Sentinel::getUser()->id;
			   $userser = UserService::checkService($this->userId);
			
			    if($userser)
			    {
					
					//echo  "<pre>"; print_r($userser); die;
					if($userser->agree_terms=='No') 	
					 return Redirect::route('seller.term-condition');
					elseif(!Sentinel::inRole('seller') && !Sentinel::inRole('user'))
					 return Redirect::route('seller.landing');
				}
				else{
				 return Redirect::route('seller.landing');
			 }
					
			   
		   }

        return $next($request);
    }
}
