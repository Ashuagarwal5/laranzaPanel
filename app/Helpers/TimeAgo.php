<?php

namespace App\Helpers;

class TimeAgo {

   public function time_elapsed_string($datetime, $full = false) {
		
		$now = strtotime(date('Y-m-d H:i:s'));
		$ago = strtotime($datetime);
		$etime = strtotime(date('Y-m-d H:i:s')) - strtotime($datetime);

		if ($etime < 1)
		{
			return '0 seconds';
		}

		$a = array( 365 * 24 * 60 * 60  =>  'year',
					 30 * 24 * 60 * 60  =>  'month',
						  24 * 60 * 60  =>  'day',
							   60 * 60  =>  'hour',
									60  =>  'minute',
									 1  =>  'second'
					);
		$a_plural = array( 'year'   => 'years',
						   'month'  => 'months',
						   'day'    => 'days',
						   'hour'   => 'hours',
						   'minute' => 'minutes',
						   'second' => 'seconds'
					);

		foreach ($a as $secs => $str)
		{
			$d = $etime / $secs;
			if ($d >= 1)
			{
				$r = round($d);
				return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
			}
		}
			
			
		
		
		
		
	}
	   
   
   
   
   
   public static function time()
   {
	  
		///$dateformate=config('sitesetting.Date Format');
		///$date="'".$dateformate."'";
		//return $date;
	   
   } 
   

}
