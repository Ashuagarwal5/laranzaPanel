<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as Eloquent;
class ContactUs extends  Eloquent 
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'enquiry';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable   = [];
    protected $primarykey = ['enq_id'];
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
   public static function emailDetail()
   {
	   $packgaeDetail = Contactus::select(['name','mobileno','email','message'])
	    ->orderBy('enq_id','desc')
	    ->limit('1')
		->first();
		return $packgaeDetail;
   } 
}