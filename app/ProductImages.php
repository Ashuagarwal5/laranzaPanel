<?php

namespace App;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cache;

class ProductImages extends  EloquentUser  
{
      // use Sluggable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_images';

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

    public static function get_image($product_id)
    {
        $data =  ProductImages::where('product_id', $product_id)->orderBy('display_order', 'asc')->first();
        return $data->image;
    }


	function __construct()
    {   
        parent::__construct();
      	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip());
      	
    }

    public static function get_images($product_id)
    {
       return ProductImages::where('product_id', $product_id)->orderBy('display_order', 'asc')->get();
    }
}
