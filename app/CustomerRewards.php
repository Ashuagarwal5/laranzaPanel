<?php namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as Eloquent;
use DB;
use URL;
use App\Helpers\Thumbnail;

class CustomerRewards extends Eloquent   {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'customer_rewards';

	/* * The attributes to be fillable from the model.
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
    
    
    public static function get_reward_list($params)
    {
		
		$result = array();
		$status = 'error';
		$msg = '';
		
		$rewards = CustomerRewards::select('id','qualification_points','reward','description',
			DB::raw("IF(STRCMP(tbl_customer_rewards.image,''),CONCAT('" . URL::to(Thumbnail::image("customer-reward","500","500","ff=ffffff")) . "/"."',tbl_customer_rewards.image),CONCAT('" . URL::to('/') . config('constants.admin.no_image_found') . "')) as reward_image", false)
			)->get();
			
		
		if(!empty($rewards))
		{
			$status = 'success';
			$msg = 'Data Found';
			$result['data']	= $rewards;
		}else{
			$status = 'error';
			$msg = 'Data Not Found';
		}
        
        $result['replyStatus']	= $status;
		$result['replyMessage'] = $msg;

		return $result;
	
	}
	
	public static function redeem_product($params)
	{
		
		$result = array();
		$status = 'error';
		$msg = '';
		
		$reward_id = $params['reward_id'];
		$user_id = $params['user_id'];
		$type = 'Redeem';
		
		try{
			
			$u_curr_point = CustomerPoints::select('current_points')->where('user_id',$user_id)->orderBy('created_at','desc')->first();
			$reward = CustomerRewards::select('qualification_points')->where('id',$reward_id)->first();
			
			if($u_curr_point->current_points >= $reward->qualification_points)
			{
				$balance = CustomerRewards::balance_points($user_id, $type, $reward->qualification_points);
				
				$redeem_cp = new CustomerPoints();
				$redeem_cp->user_id = $user_id;
				$redeem_cp->reward_id = $reward_id;
				$redeem_cp->point = $reward->qualification_points;
				$redeem_cp->current_points = $balance;
				$redeem_cp->transaction_type = $type;
				$redeem_cp->status = 'Active';
				$redeem_cp->save();
				
				
				$result['qualification_points']	= $reward->qualification_points;
				
				
				
				$status = "success";
				$msg = "You have redeemed this reward successfully.";
				
			}else{
				$diff = $reward->qualification_points - $u_curr_point->current_points;
				
				$status = "error";
				$msg = "You need ".$diff." Points more to redeem this reward.";
			}
			
		}catch (\Illuminate\Database\QueryException $e){
			$status = 'error';
			$msg	= "Error : ".$e->getMessage();
		} catch (PDOException $e) {
			$status = 'error';
			$msg	= "Error : ".$e->getMessage();
		} catch (\Exception $e) {
			$status = 'error';
			$msg	= $e->getMessage();
		}
		
		$result['replyStatus']	= $status;
		$result['replyMessage'] = $msg;

		return $result;
		
	}
   
   public static function my_points($params)
	{
		
		$result = array();
		$status = 'error';
		$msg = '';
		
		$user_id = $params['user_id'];
		
		try{
			
			$myPoints = CustomerPoints::select('current_points','transaction_type','point',DB::raw("(DATE_FORMAT(tbl_customer_points.created_at,'%d/%m/%y')) as date"))->where('user_id',$user_id)->orderBy('created_at','desc')->get();
			
			if(!empty($myPoints))
			{
				$status = 'success';
				$msg = 'Data Found';
				$result['data']	= $myPoints;
			}else{
				$status = 'error';
				$msg = 'Data Not Found';
			}
			
			
		}catch (\Illuminate\Database\QueryException $e){
			$status = 'error';
			$msg	= "Error : ".$e->getMessage();
		} catch (PDOException $e) {
			$status = 'error';
			$msg	= "Error : ".$e->getMessage();
		} catch (\Exception $e) {
			$status = 'error';
			$msg	= $e->getMessage();
		}
		
		$result['replyStatus']	= $status;
		$result['replyMessage'] = $msg;

		return $result;
		
	}
   
   
	public static function balance_points($user_id, $type, $points)
	{

		if($type == "Earn")
		{
			$u_curr_point = CustomerPoints::select('current_points')->where('user_id',$user_id)->orderBy('created_at','desc')->first();
			$balance = $u_curr_point->current_points + $points;
		}
		
		if($type == "Redeem")
		{
			$u_curr_point = CustomerPoints::select('current_points')->where('user_id',$user_id)->orderBy('created_at','desc')->first();
			$balance = $u_curr_point->current_points - $points;
		}   
		
		return $balance;

	}
   
}
  

   
