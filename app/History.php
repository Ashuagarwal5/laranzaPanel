<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;


class History extends Eloquent {

       use SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'qrcode_history';

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
	
	/**
	* To allow soft deletes
	*/

    protected $dates = ['created_at'];
    
    function __construct()
    {
        parent::__construct();
      	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip(),'site_id'=>config('constants.siteinfo.site_id'));
    }

}
