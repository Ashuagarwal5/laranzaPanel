<?php namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Redirect;
use DB;
use Cache;

class Banip extends Eloquent {

   
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'ban_ip';

	/**
	 * The attributes to be fillable from the model.
	 *
	 * A dirty hack to allow fields to be fillable by calling empty fillable array
	 *
	 * @var array
	 */
	protected $primaryKey = 'ban_id';
	protected $fillable = [];
	protected $guarded = ['ban_id'];
	
 
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
   
  public static function ipBan($ip) 
	{
	  $result=DB::table('ban_ip')->select('ban_ip')->where('ban_ip',$ip)->get();
		if($result){
			  
			return Redirect::route('ban_ip');
			 
			}
		
	} 
	public static function getBanIpList(){
		//Cache::flush('banIpList');
		$value=Cache::rememberForever('banIpList', function(){
		$ip=Banip::get();
		$banIpArray = array();
		foreach($ip as $key=>$data){
			$banIpArray[$key]=$data->ban_ip;
		}
		return $banIpArray;
		});
		return $value;
	} 

}
