<?php

namespace App\Helpers;

class limitedcharacter {

   public static function DisplayLimitesCharacter($desc,$length)
   {
	   
	  $description=substr($desc,0,$length);
	  if(strlen($desc)>$length)
		$description.="..";
	  return $description;
   } 
}
