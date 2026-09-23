<?php namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as Eloquent;
use DB;
use App\Helpers\common;
class CustomerPoints extends Eloquent   {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'customer_points';

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
	
  public static function getUserBalance($USERID)
  {
  		$totalEarn = CustomerPoints::select('id', DB::raw('SUM(point) AS total_earn'))->where('transaction_type','Earn')->where('user_id',$USERID)->first();
		$totalRedeem = CustomerPoints::select('id', DB::raw('SUM(point) AS total_redeem'))->where('transaction_type','Redeem')->where('user_id',$USERID)->first();
		
		$pointSummary['total_earn'] 	= $totalEarn->total_earn;
		$pointSummary['total_redeem'] 	= $totalRedeem->total_redeem;
		$pointSummary['balance'] = $pointSummary['total_earn'] - $pointSummary['total_redeem'];
		
		return $pointSummary;

  }
  
  
  #=>=>=>=>=>=>=>=>=>=>Function to Redeem Points of the User=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>	
   public static function redeemPoints($params)
   {
	    $result=array();
		$error = false;
		$minimumRedeemablePoints = WebsiteSetting::where('id',1)->first()->min_req_points;
		 try
		 {
			 
			   $data   =		self::select('customer_points.id','customer_points.current_points','customer_points.point','customer_points.user_id')
			   					  	  ->join('users', 'users.id', '=', 'customer_points.user_id')
			   					      ->where('users.id',$params['user_id'])
			   					      ->orderBy('id', 'desc')->first();
			   
			   
			   if($data)
			   {
				   if($data->current_points >= $params['qty']) //If user has sufficiet points available
				   {
					    
					     if($params['qty'] >= $minimumRedeemablePoints) //If user has minimum allowed redeemable points
					     {
						     $current_points=$data->current_points-$params['qty'];
						   
							 $obj = new CustomerPoints();
							 
							 $obj->user_id=$data->user_id;
							 $obj->dealer_id=$params['user_id'];
							 $obj->transaction_type='Redeem';
							 $obj->point=$params['qty'];
							 $obj->current_points=$current_points;
							 $obj->description='Redeemed by User';
							 $obj->save();
							 
							 
						    //User::where('id', $data->user_id)->update(['verification_code' => '']);
							/*
							
							$msgwhat=array();
						   
						    $message="Hi User,\n\n*".$params['qty']."* Points has been redeem successfully and Current Balance is *".$current_points."*\n\nFavo Mitra";
							$msgwhat['args']=array('to'=>$params['mobileno'],'content'=>$message);
							$msgwhat['method']='sendText';
							User::whatsapp($msgwhat);
						    */
						    
						    $result['point']	=	@CustomerPoints::select('current_points')
												->where('user_id',$params['user_id'])
												->orderBy('id', 'desc')->first()->current_points; 
						    
							$sendMSG = SendMessage::getSendMessage('Redeem Points',$params['user_id'],$params['qty']);
												
						    $status = 'success';
						    $msg = 'Your point redeem request submitted successfully.';
					    }
					    else
					    {
						   $status = 'error';
						   $msg = 'You donot have sufficient reward points to redeem';

					    }
				   }
				   else
				   {
					   $status = 'error';
					   $msg = 'Your redeem point is low';
				   }
			   }
			   else
			   {
				   $status = 'error';
				   $msg = 'OTP not match';
			   }
		   
		 } catch (\Illuminate\Database\QueryException $e) {
			   $status = 'error';
			   $msg = "Error : " . $e->getMessage();
			 } catch (PDOException $e) {
			   $status = 'error';
			   $msg = "Error : " . $e->getMessage();
			 } catch (\Exception $e) {
			   $status = 'error';
			   $msg = $e->getMessage();
			 }
		if ($status == 'success') {
			  $statusType = true;
			} else {
			  $statusType = false;
			}
			$result['replyStatus'] = $statusType;
			$result['replyMessage'] = $msg;
			return $result;
   }


   #<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=#
/*	
	
   public static function verifyredeempoints($params)
   {
	   $result=array();
		$error = false;
		 try
		 {
			 
			   $data=self::select('customer_points.id','customer_points.current_points','customer_points.point','customer_points.user_id')
			   ->join('users', 'users.id', '=', 'customer_points.user_id')
			   ->where('users.verification_code',$params['otp'])
			   ->where('users.mobileno',$params['mobileno'])->orderBy('id', 'desc')->first();
			   
			   if($data)
			   {
				   if($data->current_points >= $params['qty'])
				   {
					    $current_points=$data->current_points-$params['qty'];
					   $obj = new CustomerPoints();
						 
						 $obj->user_id=$data->user_id;
						 $obj->dealer_id=$params['user_id'];
						 $obj->transaction_type='Redeem';
						 $obj->point=$params['qty'];
						 $obj->current_points=$current_points;
						 $obj->save();
					    User::where('id', $data->user_id)->update(['verification_code' => '']);
					   $msgwhat=array();
					   
					   $message="Hi User, \n\n  *".$params['qty']."* Points has been redeem successfully and Current Balance is *".$current_points."*  \n\n Prime Comfort";
						 $msgwhat['args']=array('to'=>$params['mobileno'],'content'=>$message);
						 $msgwhat['method']='sendText';
						 User::whatsapp($msgwhat);
					   $status = 'success';
					   $msg = 'Your point has been redeem successfully.';
				   }
				   else
				   {
					   $status = 'error';
					   $msg = 'Your redeem point is low';
				   }
			   }
			   else
			   {
				   $status = 'error';
				   $msg = 'OTP not match';
			   }
			   
			   
			 
		   
		 } catch (\Illuminate\Database\QueryException $e) {
			   $status = 'error';
			   $msg = "Error : " . $e->getMessage();
			 } catch (PDOException $e) {
			   $status = 'error';
			   $msg = "Error : " . $e->getMessage();
			 } catch (\Exception $e) {
			   $status = 'error';
			   $msg = $e->getMessage();
			 }
		if ($status == 'success') {
			  $statusType = true;
			} else {
			  $statusType = false;
			}
			$result['replyStatus'] = $statusType;
			$result['replyMessage'] = $msg;
			return $result;
   }
   public static function redeempoints($params)
   {
	   $result=array();
		 $error = false;
		  try
		  {
			  
				$data=self::select('customer_points.id','customer_points.current_points','customer_points.point','customer_points.user_id')
				->join('users', 'users.id', '=', 'customer_points.user_id')
				->where('users.mobileno',$params['mobileno'])->orderBy('id', 'desc')->first();
				
				if($data)
				{
					if($data->current_points >= $params['qty'])
					{
						$verification_code = common::generateRandomNumber(4);
						User::where('id', $data->user_id)->update(['verification_code' => $verification_code]);
						$msgwhat=array();
						
						$message="Hi User, \n\n *" . $verification_code . "* is your OTP. Provide this OTP to the Dealer to redeem your point  \n\n Prime Comfort";
						  $msgwhat['args']=array('to'=>$params['mobileno'],'content'=>$message);
						  $msgwhat['method']='sendText';
						  User::whatsapp($msgwhat);
						$status = 'success';
						$msg = 'OTP send  Successfully to Register Mobile no';
					}
					else
					{
						$status = 'error';
						$msg = 'Your redeem point is low';
					}
				}
				else
				{
					$status = 'error';
					$msg = 'Incorrect mobile no';
				}
				
				
			  
			
		  } catch (\Illuminate\Database\QueryException $e) {
				$status = 'error';
				$msg = "Error : " . $e->getMessage();
			  } catch (PDOException $e) {
				$status = 'error';
				$msg = "Error : " . $e->getMessage();
			  } catch (\Exception $e) {
				$status = 'error';
				$msg = $e->getMessage();
			  }
		 if ($status == 'success') {
			   $statusType = true;
			 } else {
			   $statusType = false;
			 }
			 $result['replyStatus'] = $statusType;
			 $result['replyMessage'] = $msg;
			 return $result;
   }
   
*/   

   public static function rewardHistory($params)
   {
	   $result=array();
	   $error = false;
	   try
		  {		
		  		 $result['data']=	self::select('customer_points.id','current_points','point','description', 									'customer_points.created_at','transaction_type','users.full_name')
		  		 					//->where('dealer_id',$params['user_id'])
		  		 					->where('user_id',$params['user_id'])		//Added on 11 Nov 2022
		  		 					->where('transaction_type','Earn')
									->join('users', 'users.id', '=', 'customer_points.user_id')
									->orderBy('id', 'desc')->get();
				$user_status = 'New';
				$status = 'success';
				$msg = '';
			  
			
		  } catch (\Illuminate\Database\QueryException $e) {
				$status = 'error';
				$msg = "Error : " . $e->getMessage();
			  } catch (PDOException $e) {
				$status = 'error';
				$msg = "Error : " . $e->getMessage();
			  } catch (\Exception $e) {
				$status = 'error';
				$msg = $e->getMessage();
			  }
		 if ($status == 'success') {
			   $statusType = true;
			 } else {
			   $statusType = false;
			 }
			 $result['replyStatus'] = $statusType;
			 $result['replyMessage'] = $msg;
			 return $result;
	}
  
  
   public static function redeemHistory($params)
   {
	   $result=array();
	   $error = false;
	   try
		  {		
		  		 $data =	self::select('customer_points.id','current_points','point','reward_status', 									'customer_points.created_at','transaction_type','users.full_name')
		  		 					//->where('dealer_id',$params['user_id'])
		  		 					->where('user_id',$params['user_id'])		//Added on 11 Nov 2022
		  		 					->where('transaction_type','Redeem')
									->join('users', 'users.id', '=', 'customer_points.user_id')
									->orderBy('id', 'desc')->get();
									
									
				foreach($data as $key	=> $value)	
				{
					if($value->reward_status == 'approved')
					{
						$value->icon 	= 'checkbox-marked-circle-outline';
						$value->color 	= '#53B902';
					}
					elseif($value->reward_status == 'cancelled')
					{

						$value->icon = 'close';
						$value->color = '#D42018';
					}
					else
					{

						$value->icon 	= 'circle-outline';
						$value->color 	= 'grey';

					}
					
					$value->reward_status = ucfirst($value->reward_status);
				}				
									
									
				$result['data']		= $data;				
									
				$user_status = 'New';
				$status = 'success';
				$msg = '';
			  
			
		  } catch (\Illuminate\Database\QueryException $e) {
				$status = 'error';
				$msg = "Error : " . $e->getMessage();
			  } catch (PDOException $e) {
				$status = 'error';
				$msg = "Error : " . $e->getMessage();
			  } catch (\Exception $e) {
				$status = 'error';
				$msg = $e->getMessage();
			  }
		 if ($status == 'success') {
			   $statusType = true;
			 } else {
			   $statusType = false;
			 }
			 $result['replyStatus'] = $statusType;
			 $result['replyMessage'] = $msg;
			 return $result;
	}
   
   //========History of Reward Points incoming and outgoing QR Scan and Redeem Points=======# 
   public static function AppList($params) {
	  			$result=array();
	  			$error = false;
	   try
	   {
		     $result['data']=self::select('customer_points.id','current_points','point', 
		     				'customer_points.created_at','transaction_type','coupon_no','customer_points.qr_value', 							'products.product_group_code','products.density',
		     				'products.length', 'products.width','products.thickness','products.varient')
			 				->leftJoin('products', 'products.id', '=', 'customer_points.product_id')
			 				->where('user_id',$params['user_id'])
			 				->orderBy('id', 'desc')
			 				->get();
			 
			 $user_status = 'New';
			 $status = 'success';
			 $msg = '';
		   
		 
	   } catch (\Illuminate\Database\QueryException $e) {
			 $status = 'error';
			 $msg = "Error : " . $e->getMessage();
		   } catch (PDOException $e) {
			 $status = 'error';
			 $msg = "Error : " . $e->getMessage();
		   } catch (\Exception $e) {
			 $status = 'error';
			 $msg = $e->getMessage();
		   }
	  if ($status == 'success') {
			$statusType = true;
		  } else {
			$statusType = false;
		  }
		  $result['replyStatus'] = $statusType;
		  $result['replyMessage'] = $msg;
		  return $result;
	 }



   //========This function checks if customer points are matching with Bonus Table=======# 
   public static function checkAwardBonusPoints($userId, $currentPointsBalance) 
   {
		    
		    $isBonusFound = 'No';
		    //Check if $currentPointsBalance fall into bonus point table
		    /*
		    $bonusRewardRecord= CustomerRewards::select('*')
				 				->where('ponts_from','>=', $currentPointsBalance)
				 				->where('ponts_to','<=', $currentPointsBalance)
				 				->where('level','>', $bonusStepCompletd)
				 				->orderBy('id', 'desc')
				 				->first();
			
			dd($bonusRewardRecord);
			*/ 
			
			$userBonusStepRecord = User::select('bonus_step_completed')->where('id',$userId)->first();
			$bonusStepCompletd = $userBonusStepRecord->bonus_step_completed;
			
			$bonusRewardRecord= DB::select('SELECT * FROM `tbl_customer_rewards` 
									WHERE '.$currentPointsBalance.' >= points_from and '.$currentPointsBalance.' <=  	points_to  
									and level > '.$bonusStepCompletd.';');
				 
			 
			 //Award Bonus Point if Applicable
			 if(isset($bonusRewardRecord) && !empty($bonusRewardRecord))
			 {
				$currentBalanceAfterReward = $currentPointsBalance+$bonusRewardRecord[0]->bonus_ponits;
				$newBonusLevel = $bonusStepCompletd+1;
				
				
				DB::table('customer_points')->insert(
		            array(
		                'point'            => $bonusRewardRecord[0]->bonus_ponits,
		                'user_id'          => $userId,
		                'transaction_type' => 'Earn',
		                'current_points'   => $currentBalanceAfterReward,
		                'description'      => 'Bonus Point Level '.$newBonusLevel,
		                'added_from'	   => 'admin',
		            )
				);
				
				//==Update User current bonus level
				$isUpdated = User::where('id', $userId)->update([ 'bonus_step_completed' => $newBonusLevel]);
				
				$isBonusFound = 'Yes';
				 
			 }
			 
			 return $isBonusFound;
	 }

   
}
  

   
