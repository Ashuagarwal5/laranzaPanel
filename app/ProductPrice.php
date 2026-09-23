<?php

namespace App;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cache;
use Session;

class ProductPrice extends  EloquentUser  
{
    //   use Sluggable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_prices';

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

    public static function get_product($alphacode=null, $product_id=null)
    {
        $data =  ProductPrice::where('product_id', $product_id)
                    ->where('alphacode', $alphacode)
                    ->first();
        return $data;
    }
   
    public static function get_product_currency($product_id)
    {
        $data =  ProductPrice::select('product_prices.*', 'country.code as country_code')
                    ->join('country', 'country.cntry_id', 'product_prices.country')
                    ->where('product_id', $product_id)
                    ->orderBy('alphacode', 'asc')
                    ->get();
        return $data;
    }
    

    //product info according to country currency 
    public static function get_product_price($product_id)
    {
        $currency =  session()->get('currency') ? session()->get('currency') : 'INR';

        $data =  ProductPrice::select('product_prices.currency','product_prices.symbol', 'product_prices.alphacode', 
                                        'product_prices.price as product_price', 'products.*')
                    ->join('products', 'products.id', 'product_prices.product_id')
                    ->where('product_prices.product_id', $product_id)
                    ->where('product_prices.alphacode', $currency)
                    ->first();
        
        return $data;
    }


   
}
