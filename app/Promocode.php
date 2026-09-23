<?php namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use DB;
use Sentinel;

class Promocode extends Eloquent{
     
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'promo_code';

	/**
	 * The attributes to be fillable from the model.
	 *
	 * A dirty hack to allow fields to be fillable by calling empty fillable array
	 *
	 * @var array
	 */
	protected $primaryKey = 'promo_code_id';
	protected $fillable = [];
	protected $guarded = ['promo_code_id'];
   
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

       protected static function boot()
    {
        parent::boot();
        
        static::addGlobalScope('promo_code.site_id', function (Builder $builder) { 
            $builder->where('promo_code.site_id', '=',config('constants.siteinfo.site_id'));
        });
    }  
    protected $dates = ['deleted_at'];
    
	
    function __construct()
    {
        
        parent::__construct();
      	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip(),'site_id'=>config('constants.siteinfo.site_id'));
      	
    }
    
    public static function checkForSessionExpire($promo_code_id)
    {
			return Promocode::where('promo_code_id',$promo_code_id)
			->where('promo_code.from_date', '<=', date('Y-m-d'))
			->where('promo_code.to_date', '>=', date('Y-m-d'))
			->count();
	}
    public static function promocode()
    {
			return Promocode::where('promo_code.from_date', '<=', date('Y-m-d'))
			->where('promo_code.to_date', '>=', date('Y-m-d'))
			->orderBy('promo_code_id','DESC')
			->first();
	}
    
    
    
}


   
