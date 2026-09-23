<?php namespace App;


use Illuminate\Database\Eloquent\Model as Eloquent;
use DB;
use Sentinel;
class RoleUser extends Eloquent  {
  
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'role_users';

	/**
	 * The attributes to be fillable from the model.
	 *
	 * A dirty hack to allow fields to be fillable by calling empty fillable array
	 *
	 * @var array
	 */
	protected $primaryKey = 'user_id';
	protected $fillable = [''];
	protected $guarded = ['user_id'];
    
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
    
        
    public static function manager($staff_id)
    {
		$userDetail = RoleUser::select(['role_users.privileges'])
		->where('role_users.user_id',$staff_id)->first();
	
		return $userDetail;
	} 
	
	 public static function mainmanager($parent_id)
    {
		return Manager::select('manager_name','display_order','class_name','page_link','mng_id')->where('mng_id',$parent_id)->orderby('display_order','asc')->get();
	}
	
    
    
 
}
  

   
