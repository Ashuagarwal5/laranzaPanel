<?php
namespace App\Helpers;

class datehelper {

   public static function dateformat()
   {
	  
		$dateformate=config('sitesetting.Date Format');
		$date="'".$dateformate."'";
		return $date;
	   
   } 
   

}
