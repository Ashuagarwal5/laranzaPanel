<?php

namespace App;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cache;
use DB;

class City extends  EloquentUser  
{
    //   use Sluggable;

    /**
     * The database table used by the model.
     *City
     * @var string
     */
    protected $table = 'district';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable   = [];
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
      protected $hidden = [''];
    //   use SoftDeletes;

   // protected $dates = ['deleted_at'];


	// function __construct()
    // {   
    //     parent::__construct();
    //   	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip());
      	
    // }
//    public static function getAllStates() 
// 	{
// 	  $result=DB::table('city')->select('id','name')->get();
// 		return $result;
// 	} 
  public static function getDistrictById($id) 
  {
    $result=DB::table('district')->select('id','name')->where('id',$id)->first();
    return $result;
  } 

  public static function getDistrictByName($id) 
  {
    $result=DB::table('district')->select('id','name')->where('name',$id)->first();
    return $result;
  } 

}
