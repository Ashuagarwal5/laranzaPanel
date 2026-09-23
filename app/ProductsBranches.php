<?php

namespace App;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cache;
use DB;
use URL;
use App\Helpers\Thumbnail;

class ProductsBranches extends  EloquentUser  
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_branches';

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
	use SoftDeletes;

    protected $dates = ['deleted_at'];


	function __construct()
    {   
        parent::__construct();
      	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip());
      	
    }
}
