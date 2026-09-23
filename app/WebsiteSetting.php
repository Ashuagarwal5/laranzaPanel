<?php namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model as Eloquent;
use DB;
use Session;
use Cache;
class WebsiteSetting extends Eloquent  {


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'website_settings';

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


	public static function getWebsiteSettingAdmin(){
		Cache::flush('siteSettingList');
		$value=Cache::rememberForever('siteSettingList', function(){
		return WebsiteSetting::where('id',1)->first();
		});
		return $value;
	}
	
	function __construct()
    {

        parent::__construct();
      	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip(),'site_id'=>config('constants.siteinfo.site_id'));

    }
     
    public static function getGeneralSetting(){


		return WebsiteSetting::where('id',1)->first();

	}
	
	
	/***********************API Functions ********************************/
	
	public static function site_setting()
	{
		$result = array();
		$status = 'error';
		$msg = '';
		try {
		$data = WebsiteSetting::select(['contact_no','app_version','app_version_ios'])
				->where('id',1)->first();
		
		if(!empty($data))
		{
			$status = 'success';
			$msg = "Data found Successfully";
			$result['data'] = $data;
		}else{
			$status = 'error';
			$msg = "Data not found";
		}	
		
		}
	  catch (\Illuminate\Database\QueryException $e){
			$status = 'error';
			$msg	= "Error IN Query : ".$e->getMessage();
		} catch (PDOException $e) {
			$status = 'error';
			$msg	= "Error IN Query : ".$e->getMessage();
		} catch (\Exception $e) {
				$status = 'error';
				$msg	= $e->getMessage();
		}  	
		
		$result['replyStatus']	= $status;
		$result['replyMessage'] = $msg;

		return $result;
	}

	public static function getCurrencyCode()
	{
		return WebsiteSetting::select('currency_code')->where('id',1)->first()->currency_code;
	}

}
