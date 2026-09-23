<?php

namespace App;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cache;
use Cart;
use DB;
use URL;
use App\Category;
use App\Helpers\Thumbnail;

class Infobox extends  EloquentUser  
{
      // use Sluggable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'infobox';

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
    
    public static function AppList($params) {
          $result=array();
          $error = false;
           try
           {
                 $result['data']=self::select('id','title','image','file')->orderBy('id', 'desc')->simplePaginate(10);
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
}
