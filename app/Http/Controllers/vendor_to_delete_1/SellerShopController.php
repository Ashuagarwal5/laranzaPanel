<?php

namespace App\Http\Controllers\vendor;
use Illuminate\Http\Request as valRequest;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\CodespurController;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use App\SellerDetails;
use App\Http\Requests;
use App\Product;
use Session;
use Redirect;
use Sentinel;
use URL;
use View;
use DB;
use Response;
use Input;
use App\Helpers\datehelper;
class SellerShopController extends CodespurController
{

    function __construct()
     {
		 $this->date=datehelper::dateformat();
          parent::__construct();
	      if (Sentinel::check())
            {
               $this->userId = Sentinel::getUser()->id;
            }
     }
     
    public function createShop()
    {   
	 	$noOfProduct  = Product::where('seller_id',$this->userId)->count();
	 	$shop_detail = SellerDetails::select('shop_name','publish')->where('user_id',$this->userId)->first();
		if(!empty($shop_detail->shop_name))
			return Redirect::route('seller.myshop');
		else
         return view('vendor.shop.create',compact('noOfProduct'));
    }
    
    public function addShop(valRequest $request)
    {
              $this->validate($request, [
				'shop_name' => 'required|min:5|max:16|regex:/^[a-zA-Z0-9\-]+$/',
				'publish' => 'required',
                   
			]);
			$site_url =basename(URL::to('/'));
			$shopname = $request->get('shop_name');
			$shop_url = strtolower($request->get('shop_name')).'.'.$site_url;
			$publish = $request->get('publish');
			
			$alreadyExist =SellerDetails::where('user_id','!=',$this->userId)
			->where('shop_name',$shopname)
			->first();
          if($alreadyExist)
          {
				$data=array(
				'status'=>'error',
				'message'=>'Shop Name Already Exist'
				);

		  }
		  else
		  { 
			   SellerDetails::where('user_id',$this->userId)
			   ->update(['shop_name'=> $shopname,'shop_url'=> $shop_url,'publish'=>$publish]);
			   $data=array(
					'status'=>'success',
					'message'=>'Successfull Create',
					'url'=>route('seller.myshop')
					);
				if($publish=='Yes')	
			    Session::flash('message', 'Your shop has been created and published successfully.!'); 
			    else
			    Session::flash('message', 'Your shop has been created!'); 
			}
           
           return json_encode($data);
	}
	
	public function myShop()
	{   
		$shop_detail = SellerDetails::select('shop_name','publish','shop_url')->where('user_id',$this->userId)->first();
		if(!empty($shop_detail->shop_name))
			return view('vendor.shop.myshop',compact('shop_detail'));
		else
		 return Redirect::route('seller.createshop')->with('error','First Create Your Shop');
		
	}
	
	public function myShopPost()
	{
		$publish = Input::get('publish');
		SellerDetails::where('user_id',$this->userId)
		   ->update(['publish'=>$publish]);
		return json_encode(array('status'=>'success','message'=>'Successfully Updated'));    
	}
}
