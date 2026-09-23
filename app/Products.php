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

class Products extends  EloquentUser
{
       use Sluggable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable   = [];
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
	
	public function sluggable(): array
	{
		return [
			'slug' => [
				'source' => 'link'
			]
		];
	}


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
        
   public static function modulename($product_id)
   {
	$data = Products::where('id',$product_id)->first();
	if ($data != null) {
		$category = Category::where('id',$data->category_id)->first();
	} else {
		$category = null;
	}
	echo $data->product_title .'&nbsp;'. $category->category_name;
   }
    
   public static function productMeasurements($PRDID)
   {
	   
	   	$data = self::where('id',$PRDID)->first();
	   	return $data;
	   
   }
    
    public static function AppReward($params)
	{
		$result=array();
		$error = false;
		$point=0;
		$temp=true;
		$qrcode=$params['qrcode'];
		
		$params['latitude']	= NULL;			//Currently Sending null
		$params['longitude']= NULL;
					

		try {
			
			
			/*
			if(strlen($params['qrcode'])<10)
			{

				$temp=false;
			}
			else
			{

				$qrcode=substr($params['qrcode'],0,-6);
			}

			*/
			
			//====Check if QR Code exists in database ====================#	
			$product=self::where('qr_value', '=', $qrcode)->first();
			
			if($product && $temp)
			{
				$isQRAlreadyUsed=CustomerPoints::select('current_points')->where('qrcode_no',$params['qrcode'])->count();
				
				if($isQRAlreadyUsed)
				{
					$status = 'error';
					$msg = 'This QR Code already used.';
				}
				else
				{
					//=====Get Current Point of the user ======#
					$point=		@CustomerPoints::select('current_points')->where('user_id',$params['user_id'])
												->orderBy('id', 'desc')->first()->current_points;
												
					$dealerId = User::where('id',$params['user_id'])->first()->dealer_id;
					//Check if this is a valid dealer
					$isValidDealer = User::checkValidDealer($dealerId);
					
					
					if(isset($isValidDealer) && !empty($isValidDealer) && $isValidDealer == 'Yes')
					{
						//======Insert Data into Customer Points===#							
						$obj= new CustomerPoints();
						$obj->user_id=$params['user_id'];
						$obj->qrcode_no=$params['qrcode'];
						$obj->qr_value=$params['qrcode'];
						$obj->product_id=$product->id;
						$obj->lt=$params['latitude'];
						$obj->lg=$params['longitude'];
						$obj->point=$product->reward_points;
						$obj->current_points=($point+$product->reward_points);
						$obj->transaction_type='Earn';
						$obj->dealer_id=$dealerId;
						$obj->description='Reward Point-QR Scan';
						$obj->save();
						
						
						//=====Update product product for used status=================#
						self::where("id",$product->id)->update(['used_status'=>'Yes']);
	
						
						//Send updated current points of the user in the result=======# 
						$result['point']	=	@CustomerPoints::select('current_points')
													->where('user_id',$params['user_id'])
													->orderBy('id', 'desc')->first()->current_points;
													
						//============Check if User is applicable for bonus points if yes then award bonus points============#
						$bonusStatus = 	CustomerPoints::checkAwardBonusPoints($params['user_id'], $result['point']);
						
						//If bouns given then send updated points to the app
						if($bonusStatus == 'Yes')
						{
							$result['point']	=	@CustomerPoints::select('current_points')
													->where('user_id',$params['user_id'])
													->orderBy('id', 'desc')->first()->current_points;
						}
						//===================================================================================================#
	
	
						$status = 'success';
						$sendMSG = SendMessage::getSendMessage('Add Points',$params['user_id'],$product->reward_points);
						$msg = $product->reward_points.' Reward Points Added Successfully';
					}
					else
					{
						$status = 'error';
					    $msg = 'Dealer Not Found';
					}
				
				}

			}
			else{
				$status = 'error';
			    $msg = 'You have scan wrong QR Code.';
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
    /*************************** Api functions *****************************/

    public static function store_product($params)
	{
		$result = array();
		$status = 'error';
		$msg = '';



		try {


		$perPage=12;

		if ( ! array_key_exists ( 'page' , $params ) )
		$page=1;
		else
		$page=$params['page'];

		$offset = ($page * $perPage) - $perPage;



		$store_id =  $params['store_id'];

		$query = Products::leftjoin('category','category.id','products.sub_category')
			->where('products.seller_id',$store_id);

		if(isset($params['cat_id']) && $params['cat_id'] != null)
		{
			$query->where('products.sub_category',$params['cat_id']);
		}

		if(isset($params['subcat_id']) && $params['subcat_id'] != null)
		{
			$query->where('products.sub_part_category',$params['subcat_id']);
		}

		$query = $query->where('products.status','Active');


		if(isset($params['keyword']))
		{

			if($params['keyword'] != ''){

				$keyword   = $params['keyword'];

		    	$query->where(function ($query2) use($keyword){

				$query2->orWhere('products.product_title','like','%'.$keyword.'%');
				$query2->orWhere('products.product_description','like','%'.$keyword.'%');

				});
			}
		}


		$query->select('products.id','products.stock_status','products.product_title','products.product_description','products.sale_price','products.unit',
			DB::raw("IF(STRCMP(tbl_products.product_image,''),CONCAT('" . URL::to(Thumbnail::image("products","500","500","ff=ffffff")) . "/"."',tbl_products.product_image),CONCAT('" . URL::to('/') . config('constants.admin.no_image_found') . "')) as product_image", false)
			);

		if(isset($params['cart_id']) && $params['cart_id']!=null)
		{
			$query->addSelect(DB::raw("(SELECT SUM(tbl_shop_cart.qty) FROM tbl_shop_cart WHERE (tbl_shop_cart.cart_id = ".$params['cart_id']." AND tbl_shop_cart.product_id = tbl_products.id)) as cart_qty"));

		}

		if(isset($params['user_id']) && $params['user_id'] != null)
		{
			$query->addSelect(DB::raw("(SELECT tbl_wishlist.product_id FROM tbl_wishlist WHERE (tbl_wishlist.user_id = ".$params['user_id']." AND tbl_wishlist.product_id = tbl_products.id)) as fav_id"));
		}

		$query->groupBy('products.id');


		$products = $query->skip($offset)->take($perPage)->get();


		//$products = $query->get();


		if(!empty($products))
		{
			$status = 'success';
			$msg = 'Successfull';
			$result['data']	= $products;
		}else{
			$status = 'error';
			$msg = 'No products';
			$result['data']	= array();
		}

        $result['currency_code'] = WebsiteSetting::getCurrencyCode();
        $result['perPage'] = $perPage;


         }catch (\Illuminate\Database\QueryException $e){
		$status = 'error';
		$msg = "Error IN Query : ".$e->getMessage();
		} catch (PDOException $e) {
		$status = 'error';
		$msg = "Error IN Query : ".$e->getMessage();
		} catch (\Exception $e) {
		$status = 'error';
		$msg = $e->getMessage();
		}


        $result['replyStatus']	= $status;
		$result['replyMessage'] = $msg;

		return $result;
	}

	public static function get_all_store_cates($params)
	{
		$result = array();
		$status = 'error';
		$msg = '';


		try {
		$store_id =  $params['store_id'];

		$query = Products::join('category','category.id','products.sub_category')
			->where('products.seller_id',$store_id);


		$categories = $query->select('category.category_name','category.id')->groupBy('category.id')->where('products.status','Active')->get();


		foreach($categories as $key=> $value){


            $sub_cats =  Category::select('category.category_name','category.id')
            ->join('products','products.sub_part_category','category.id')
            ->where('products.seller_id',$store_id)
            ->where('products.status','Active')
            ->where('parent_menu',$value->id)
            ->groupBy('category.id')
            ->get();

			$value->sub_cate = $sub_cats;
			//$value->sub_cate =  Category::select('category.category_name','category.id')->where('parent_menu',$value->id)->groupBy('category.id')->get();

		}


		if(!empty($categories))
		{
			$status = 'success';
			$msg = 'Successfull';
			$result['data']	= $categories;
		}else{
			$status = 'error';
			$msg = 'No products';
		}


        }catch (\Illuminate\Database\QueryException $e){
		$status = 'error';
		$msg = "Error IN Query : ".$e->getMessage();
		} catch (PDOException $e) {
		$status = 'error';
		$msg = "Error IN Query : ".$e->getMessage();
		} catch (\Exception $e) {
		$status = 'error';
		$msg = $e->getMessage();
		}


        $result['replyStatus']	= $status;
		$result['replyMessage'] = $msg;

		return $result;
	}


	public static function search_product($params)
	{
		$result = array();
		$status = 'error';
		$msg = '';

		try {


		$keyword = $params['keyword'];
		$store_id = $params['store_id'];

		$perPage=12;

		if ( ! array_key_exists ( 'page' , $params ) )
		$page=1;
		else
		$page=$params['page'];

		$offset = ($page * $perPage) - $perPage;



		$query = Products::select('products.id','products.product_title','products.product_description','products.sale_price','products.unit',
			DB::raw("IF(STRCMP(tbl_products.product_image,''),CONCAT('" . URL::to(Thumbnail::image("products","500","500","ff=ffffff")) . "/"."',tbl_products.product_image),CONCAT('" . URL::to('/') . config('constants.admin.no_image_found') . "')) as product_image", false)
			);

			$query->where('products.seller_id',$store_id);

		if($keyword != "")
		{
			$query->where(function ($query2) use($keyword){

				$query2->orWhere('products.product_title','like','%'.$keyword.'%');
				$query2->orWhere('products.product_description','like','%'.$keyword.'%');

				});
		}

		if(isset($params['cat_id']))
		{
			$query->where('products.sub_category',$params['cat_id']);
		}

	   if(isset($params['subcat_id']) && $params['subcat_id'] != null)
		{
			$query->where('products.sub_part_category',$params['subcat_id']);
		}

		$query = $query->where('products.status','Active');

		if(isset($params['cart_id']) && $params['cart_id']!=null)
		{
			$query->addSelect(DB::raw("(SELECT SUM(tbl_shop_cart.qty) FROM tbl_shop_cart WHERE (tbl_shop_cart.cart_id = ".$params['cart_id']." AND tbl_shop_cart.product_id = tbl_products.id)) as cart_qty"));

		}

		if(isset($params['user_id']) && $params['user_id'] != null)
		{
			$query->addSelect(DB::raw("(SELECT tbl_wishlist.product_id FROM tbl_wishlist WHERE (tbl_wishlist.user_id = ".$params['user_id']." AND tbl_wishlist.product_id = tbl_products.id)) as fav_id"));
		}

		$query->groupBy('products.id');


		$products = $query->skip($offset)->take($perPage)->get();
		//$products = $query->get();

		if(!empty($products))
		{
			$status = 'success';
			$msg = 'Successfull';
			$result['data']	= $products;
		}else{
			$status = 'error';
			$msg = 'No products';
			$result['data']	= array();
		}




        $result['perPage'] = $perPage;


        }catch (\Illuminate\Database\QueryException $e){
		$status = 'error';
		$msg = "Error IN Query : ".$e->getMessage();
		} catch (PDOException $e) {
		$status = 'error';
		$msg = "Error IN Query : ".$e->getMessage();
		} catch (\Exception $e) {
		$status = 'error';
		$msg = $e->getMessage();
		}



        $result['replyStatus']	= $status;
		$result['replyMessage'] = $msg;

		return $result;
	}






//==================================================Extra====================================================//
	public static function cat_product($params)
	{
		$result = array();
		$status = 'error';
		$msg = '';

		try {

		$cat_id =  $params['cat_id'];

		$query = Products::select('products.id','products.product_title','products.product_description','products.sale_price','products.unit',
			DB::raw("IF(STRCMP(tbl_products.product_image,''),CONCAT('" . URL::to(Thumbnail::image("products","100","100","ff=ffffff")) . "/"."',tbl_products.product_image),CONCAT('" . URL::to('/') . config('constants.admin.default_user') . "')) as product_image", false)
			);

		if(isset($params['cat_level']))
		{
			if($params['cat_level'] != null)
			{
				$query->where('products.'.$params['cat_level'],$cat_id);
			}
		}
		else{
			$query->where('products.sub_category',$cat_id);
		}

		$products = $query->get();
		if(!empty($products))
		{
			$status = 'success';
			$msg = 'Successfull';
			$result['data']	= $products;
		}else{
			$status = 'error';
			$msg = 'No products';
		}

		  }catch (\Illuminate\Database\QueryException $e){
		$status = 'error';
		$msg = "Error IN Query : ".$e->getMessage();
		} catch (PDOException $e) {
		$status = 'error';
		$msg = "Error IN Query : ".$e->getMessage();
		} catch (\Exception $e) {
		$status = 'error';
		$msg = $e->getMessage();
		}

        $result['replyStatus']	= $status;
		$result['replyMessage'] = $msg;

		return $result;
	}

	//get all cart products
	public static  function get_cart_products()
	{
       $cart_products =  Cart::content();
       $cart_products->no_of_product = Cart::count();
       $cart_products->total = Cart::total();
       // echo "<pre>";
    return $cart_products ;
	}

	public static function get_links()
	{
		//get all availale products link
		return Products::select('link', 'slug')
		       ->where('deleted_at', null)
		       ->where('status', 'Active')
		       ->get();
	}


}
