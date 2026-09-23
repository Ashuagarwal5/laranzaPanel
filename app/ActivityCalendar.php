<?php namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
//use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Cache;
use Illuminate\Database\Eloquent\Model as Eloquent;
use DB;
use Session;
class ActivityCalendar extends Eloquent {

     // use Sluggable;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'activity_calendar';

	/**
	 * The attributes to be fillable from the model.
	 *
	 * A dirty hack to allow fields to be fillable by calling empty fillable array
	 *
	 * @var array
	 */
	
	protected $primaryKey = 'act_cal_id';
	protected $fillable = [];
	protected $guarded = ['	act_cal_id'];
	//protected $sluggable = [
	//'build_from' => 'event',
	//'save_to' => 'slug',
	//];
 
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
