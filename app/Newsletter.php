<?php

namespace App;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\SoftDeletes;


class Newsletter extends  EloquentUser  
{
   

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'newsletter';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable   = ['email'];
    protected $primaryKey = 'news_id';
    protected $guarded = ['news_id'];
    
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
      	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip(),'site_id'=>config('constants.siteinfo.site_id'));
      	
    }
    
    
    
  

   
}
