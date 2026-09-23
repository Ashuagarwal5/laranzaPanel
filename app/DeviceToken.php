<?php 
namespace App;
use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use DB;
use URL;
use App\Helpers\Thumbnail;

class DeviceToken extends EloquentUser {


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'device_tokens';

	

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
	  //~ protected static function boot()
    //~ {
        //~ parent::boot();
        
        //~ static::addGlobalScope('order_status.site_id', function (Builder $builder) { 
            //~ $builder->where('order_status.site_id', '=',config('constants.siteinfo.site_id'));
        //~ });
    //~ }

    protected $dates = ['created_at'];
    
 
 function __construct()
    {
        
        parent::__construct();
      	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip());
      	
    }
    
    
    
    /**************************** API Function ***********************************/
 
	public static function update_device_token($params)
	{
		$user_id = $params['user_id'];
		$device_token = $params['device_token'];
		$device_type = $params['device_type'];
		
		$result = array();
		$status = 'error';
		$msg = '';
		
		$user = User::where('id',$user_id)
		                       ->first();
		
		if(!empty($user))
		{

			
			$item['user_id']          = $user_id;
			$item['device_token']     = $device_token;
			$item['device_type']      = $device_type;

			$where_cond['user_id']     =  $user_id;
			$where_cond['device_type'] =  $device_type;
			$rm_update     = DeviceToken::updateOrCreate($where_cond,$item);        
				
			
			$status = 'success';
			$msg = 'Device Token Added';
			
			$result['token'] = $rm_update;
		}
		else{
			$status = 'error';
			$msg = 'user not found';
		}
		
		
		
		$result['replyStatus']	= $status;
		$result['replyMessage'] = $msg;

		return $result;
	}
	

} 
