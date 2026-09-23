<?php namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as Eloquent;
use DB;
use Sentinel;

class Roles extends Eloquent  {
  
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'roles';

	/**
	 * The attributes to be fillable from the model.
	 *
	 * A dirty hack to allow fields to be fillable by calling empty fillable array
	 *
	 * @var array
	 */
	protected $primaryKey = 'id';
	protected $fillable = [''];
	protected $guarded = ['id'];
    use SoftDeletes;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [''];

	/**
	* To allow soft deletes
	*/
	

    protected $dates = ['deleted_at'];
    

    function __construct()
    {
        
        parent::__construct();
      	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip(),'site_id'=>config('constants.siteinfo.site_id'));
    }

    
 
}
  

   
