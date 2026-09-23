<?php
namespace App;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cache;

class Dealer extends  EloquentUser  
{
    //   use Sluggable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dealers';

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
   public static function cab()
    {
		return DEALER::orderby('id','asc')->get();
	}

  public static function checkDealerByMobileNumber($dealer_mobile)
  {
    $value = NULL;
    $data = self::where('mobile_no',$dealer_mobile)
    ->join('role_users', 'role_users.user_id', '=', 'dealers.user_id')
		->join('roles', 'roles.id', '=', 'role_users.role_id')
    ->where('roles.slug', 'dealer')
    ->first();
    if ($data != null) {
      $value = $data->user_id;
    }
    return $value;
  }



   
}
