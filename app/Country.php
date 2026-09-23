<?php

namespace App;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cache;
use DB;

class Country extends  EloquentUser  
{
    //   use Sluggable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'country';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable   = [];
    protected $primaryKey = 'cntry_id';
    protected $guarded = ['cntry_id'];

    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
      protected $hidden = [''];
       use SoftDeletes;

    protected $dates = ['deleted_at'];


	function __construct()
    {   
        parent::__construct();
      	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip());
      	
    }
   public static function getAllCountry() 
	{
	  $result=DB::table('country')->select('cntry_id','cntry_name')->orderBy('cntry_name','asc')->get();
		return $result;
	} 
	
	
		/********************** API Fuctions ***********************/

   public static function getAllCountryApi($params) 
	{
		
		$result = array();
		$status = 'error';
		$msg = '';
		$result=DB::table('country')->select('cntry_id','cntry_name')->orderBy('cntry_name','asc')->get();
		$status = 'success';
		$msg = 'Data found';

		$result['data']	= $result;

		$result['replyStatus']	= $status;
		$result['replyMessage'] = $msg;

		return $result;
		
		
	}



}
