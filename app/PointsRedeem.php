<?php

namespace App;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cache;
use Cart;
use DB;
use URL;
use App\Helpers\Thumbnail;

class PointsRedeem extends  EloquentUser  
{
      // use Sluggable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'points_redeem';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable   = [];
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
	protected $hidden = [''];
	use SoftDeletes;

    protected $dates = ['deleted_at'];


	function __construct()
    {   
        parent::__construct();
      	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip());
      	
    }
	
	
    public static function AppRedeemPoints($params)
    {
   
    
      $user_id = $params['user_id'];	
      $qty=$params['qty'];	
      $msg='';
       try {
             
             // tour basic details //
             $pointSummary=@CustomerPoints::getUserBalance($user_id);
             $pointSummary=$pointSummary['balance'];
            
            if($qty > $pointSummary)
            {
                $status = 'Error';
                $msg="Redeemable Points can't be greater than available points";
            }
            else
            {
                $oldrequest= self::where("user_id",$user_id)->where('status','Pending')->count();
                if($oldrequest==0)
                {
                    $status = 'success';
                     $obj = new PointsRedeem();
                      $obj->user_id=$user_id;
                      $obj->status='Pending';
                      $obj->points=$params['qty'];
                      $obj->save();
                      $msg="Your rewquest has been successfully Submited.";
                }
                else
                {
                    $status = 'Error';
                    $msg="Your request already submitted.";
                }

                 
            }
            
           
        }catch (\Illuminate\Database\QueryException $e){
               $status = 'error';
               $msg	= "Error IN Query : ".$e->getMessage();
          } catch (PDOException $e) {
               $status = 'error';
               $msg	= "Error IN Query : ".$e->getMessage();
         } catch (\Exception $e) {
            $status = 'error';
            $msg	= $e->getMessage();
       }  
      
      if($status == 'success')
      $statusType = true;
      else
      $statusType =  false;	
       
      $result['replyStatus'] = $statusType;
       $result['replyMessage'] = $msg; 
      
      return $result;
         
      
   }
}
