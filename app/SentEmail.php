<?php namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model as Eloquent;
use DB;
use Illuminate\Database\Eloquent\Builder;

class SentEmail extends Eloquent   {

  
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'sent_email';

	/**
	 * The attributes to be fillable from the model.
	 *
	 * A dirty hack to allow fields to be fillable by calling empty fillable array
	 *
	 * @var array
	 */
	protected $primaryKey = 'em_id';
	protected $fillable = [];
	protected $guarded = ['em_id'];
  
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
         protected static function boot()
    {
        parent::boot();
        
        static::addGlobalScope('sent_email.site_id', function (Builder $builder) { 
            $builder->where('sent_email.site_id', '=',config('constants.siteinfo.site_id'));
        });
    }
    protected $dates = ['deleted_at'];
    
    
   function __construct()
    {
        
        parent::__construct();
      	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip(),'site_id'=>config('constants.siteinfo.site_id'));
      	
    }

}
