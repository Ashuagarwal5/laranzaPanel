<?php

namespace App;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cache;
use DB;

class Wallet extends  EloquentUser  
{
    //   use Sluggable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'wallet';

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
      // use SoftDeletes;

   // protected $dates = ['deleted_at'];


	function __construct()
    {   
        parent::__construct();
      	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip());
      	
    }
    public static function mytransaction($params)
    {
   
    
      $user_id = $params['user_id'];	
       try {
             
             // tour basic details //
             $data =   Wallet::where('user_id',$user_id)
             ->orderBy('id', 'DESC')
            ->paginate(10);
            
            $result['data'] = $data;
            
            $result['commission'] = Commission::where("user_id",$user_id)->where("status",'!=','Pending')->sum('commission');
            $result['transfered'] = '0.00';
            
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
   

}
