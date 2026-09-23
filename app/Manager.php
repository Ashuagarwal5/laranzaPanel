<?php namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Manager extends Eloquent   {



	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'manager';

	/**
	 * The attributes to be fillable from the model.
	 *
	 * A dirty hack to allow fields to be fillable by calling empty fillable array
	 *
	 * @var array
	 */
	protected $primaryKey = 'mng_id';
	protected $fillable = [];
	protected $guarded = ['	mng_id'];


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
      	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip(),'site_id'=>config('constants.siteinfo.site_id'));

    }

    public static function manager()
    {
		return Manager::select('manager_name','display_order','class_name','mng_id')->where('parent_id',0)->orderby('display_order','asc')->get();
	}

	public static function getId($page_link)
    {
		return Manager::select('mng_id')->where('page_link',$page_link)->first();
	}

	 public static function submanager($parent_id)
    {
		return Manager::select('manager_name','display_order','class_name','page_link','mng_id')->where('parent_id',$parent_id)->orderby('display_order','asc')->get();
	}


}
