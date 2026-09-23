<?php namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model as Eloquent;
use DB;
use Cache;

class Pincode extends Eloquent   {

    
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'pincodes';

	/**
	 * The attributes to be fillable from the model.
	 *
	 * A dirty hack to allow fields to be fillable by calling empty fillable array
	 *
	 * @var array
	 */
	protected $primaryKey = 'id';
	protected $fillable = [];
	protected $guarded = ['id'];
   
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [''];

	/**
	* To allow soft deletes
	*/
	use SoftDeletes;
  
    protected $dates = ['deleted_at'];
    
    
    
  
  function __construct()
    {
        
        parent::__construct();
      	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip());
      	
    }



}
