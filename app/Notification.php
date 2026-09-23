<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;


class Notification extends Eloquent {

       use SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'notifications';

	

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
	

    protected $dates = ['created_at'];
    
 
 function __construct()
    {
        
        parent::__construct();
      	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip(),'site_id'=>config('constants.siteinfo.site_id'));
      	
    }
 public static function getNotification($params)
	{
   
	  $user_id = array();
	  $user_id[]=0;
	  $user_id[] = trim($params['user_id']);	
	  
	  $type=($params['type'])?'user':'customer';
	   try {
			 
			 // tour basic details //
			 $data =   Notification::select('id','title','description','created_at')
			 ->whereIn('user_id',$user_id)
			 ->orderBy('id', 'DESC')
			->paginate(10);
			
			$result['data'] = $data;
			$status = 'success';
			if(count($data)>0)
			{
			   $msg ="Detail Found";
			}
			else
			{
			   $msg = 'No Detail Found';
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
	   
	  $result['success'] = $statusType;
	   $result['message'] = $msg; 
	  
	  return $result;
		 
	  
   }
   
   public static function tourmanager($params)
   {
	  
		 $user_id = $params['user_id'];	
		 echo $bookingdate_id = $params['bookingdate_id'];
		 $msg=$params['msg'];	
		 
		  try {
				
				// tour basic details //
				$TourDates = TourDates::select('group_tour_date.*')
				
				->where('id',$bookingdate_id)->first();
				
			   $result['data'] = $data;
			   $status = 'success';
			   if(count($data)>0)
			   {
				  $msg ="Notification Successfully Send";
			   }
			   else
			   {
				  $msg = 'No Detail Found';
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
		  
		 $result['success'] = $statusType;
		  $result['message'] = $msg; 
		 
		 return $result;
   }

}
