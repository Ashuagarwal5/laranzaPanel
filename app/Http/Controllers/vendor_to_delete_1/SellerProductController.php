<?php

namespace App\Http\Controllers\vendor;

use App\Http\Requests;
use Illuminate\Http\Request as valRquest;
use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

use App\Http\Requests\SellerRequest;
use App\Http\Requests\SellerLoginRequest;
use App\Http\Requests\SellerCompanyRequest;
use App\Http\Requests\AttributesellerRequest;



use App\Http\Requests\SellerPassRequest;
use App\Http\Requests\ProductGeneralRequest;
use DB;
use File;
use Hash;
use Illuminate\Support\Facades\Request;
use Lang;
use Mail;
use Redirect;
use Sentinel;
use URL;
use Illuminate\Support\Facades\Input;
use View;
use App\User;
use App\SellerProducts;
use App\State;
use App\RoleUser;
use App\Products;
use App\Brand;
use App\TaxClass;
use App\ProductCategory;
use App\SellerDetails;
use App\ImageProduct;
use App\Attribute;
use App\AttributeValue;
use App\ProductImages;
use App\ProductAttributes;
use App\BranchStores;


use App\ProductsBranches;
use Validator;
use Storage;


use App\Category;
use Datatables;
use Response;
use Route;
use Session;
use App\Helpers\Product as prohelpers;
use App\ProductAttribute;
use App\AttributeSets;
use Cache;
use Artisan;

class SellerProductController extends Controller
{



    function __construct()
     {
		 
		 $this->middleware(function ($request, $next) {
				if (Sentinel::check()) {
			$this->userId= Sentinel::getUser()->id;
}
			return $next($request);
			});
			

	      //~ if (Sentinel::check())
            //~ {

               //~ $this->userId = Sentinel::getUser()->id;
            //~ }
     }
   public function checkApprove()
   {

	     $seller =  SellerDetails::where('status',"Approve")
	                           ->where('user_id',$this->userId)->first();
	                           
	                        
	                           
		if(empty($seller)){
		return false;

		}
		else{
		return true;
		}

	}
   
   
   
	 public function create($id=null)
    {
	
		  



		if($id!=null)
		{
			
			$product = Products::where('seller_id', $this->userId)->where('id',$id)->first();


			if(empty($product)){



			return Redirect::route('productlist');


			}
			
			
			$data = Products::find($id);
			$json_data = ProductsBranches::where('product_id',$id)->pluck('branch_id')->toArray();
						$pro_imgs = ProductImages::select('id','image','product_id')->where('product_id',$id)->get();

			//$json_data = json_decode($data->branches);
		}
		
		$sellerdetail = SellerDetails::select('category')->where('user_id',$this->userId)->first();
		
		return view('vendor.product.create',compact('PARENT_ID','data','json_data','pro_imgs','sellerdetail'));
		
	// return View('vendor.product.create',compact('ImageProduct','sets','product_basic_info','bulkdata','count','tab','product','allSubCategories','brand_list','cat_data','taxclass','relation','occasion','festivals','culturaltraditions','religion','wedding','worship','erelation','eoccasion','efestivals','eculturaltraditions','ereligion','ewedding','eworship'));

		

    }
   
   
    public function creategeneral(valRquest $request,$id=null)
    {
		
		
	
	   //~ $existingStores = ProductsBranches::where('product_id',$id)->pluck('branch_id')->toArray();
		//echo print_r($existingStores); die;
		
			$rules['product_title']			= "required|string";
			//$rules['product_type'] 			= "required";
		//	$rules['unit'] 			= "required";
			//$rules['product_description']	= "required";
			
			
			if($id!=null)
			{
				
				if($request->product_image)
				$rules['product_image']			= "mimes:jpg,jpeg,png";
			}
			else
			{
				
				$rules['product_image']			= "required|mimes:jpg,jpeg,png";
			}
			//$rules['category']		 		= "required";
			$rules['sub_category']			= "required";
			$rules['sale_price']			= "required";
			$rules['offer_price']			= "required";
			$rules['stock_status']			= "required";
			//~ $rules['branch_stores']			= "required";
			
			//~ if($request->branch_stores=="specific")
			//~ $rules['branches']			= "required";
			
			
		
			$errorMsg						= "Opps ! Please fill required fields.";
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
			}
			else{
				
			$input=$request->all();
			
			$file=$request->product_image;
			$records= Products::Select('product_image')->where('id',$id)->first();   	
	
			if (!empty($file))
			{	
				
				$extension = $file->extension();
				$folderName = '/products';
				$safeName = str_random(10) . '.' . $extension;
				@mkdir($folderName,0777,true);
				@chmod($folderName,0777);
				Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
				$input['product_image'] = $safeName;
				
				 if(count($records)>0 && ($records->product_image!='') && ($input['product_image']!=''))
				{
					$folderName = '/products';
					$filedir = $folderName .'/'. $records->product_image;
					Storage::disk('uploads')->delete($filedir);
				}
				
			}
			else 
			{
				$input['product_image'] = $records['product_image'];
			} 
			
			$input['seller_id'] = $this->userId;
			$input['added_by'] = 'Seller';
			
			
			//~ if($request->branch_stores=="specific")
			//~ {
				//~ if($input['branches'])
				//~ $input['branches'] = json_encode($input['branches']);
				//~ else
				//~ $input['branches'] = json_encode(array(0));
			//~ }else{
				//~ $input['branches'] = json_encode(array(0));
			//~ } 
			
			$sellerdetail = SellerDetails::select('category')->where('user_id',$this->userId)->first();
			
			$input['category'] = $sellerdetail->category;
			
			$pro = Products::updateOrCreate(['id' => $id], $input);
			
			//~ if($request->branch_stores == "all")
			//~ {
				//~ $stores = BranchStores::pluck('id')->toArray();
			//~ }else{
				//~ $stores = $request->branches;
			//~ }
			
			//~ if(!empty($stores))
			//~ {
				//~ if(!empty($existingStores))
				//~ {
					  //~ ProductsBranches::where('product_id', $id)
                        //~ ->whereNotIn('branch_id', $stores)
                        //~ ->forceDelete();
				//~ }
				//~ $store_remian = array_diff($stores, $existingStores);
				
				//~ foreach($store_remian as $value){
					//~ $obj = new ProductsBranches;
					//~ $obj->product_id = $pro->id;
					//~ $obj->branch_id = $value;
					//~ $obj->save();
				//~ }
			//~ }
			
		 
			if($id!=null) 
			$message = "Thank-You! Product Updates Successfully.";
			else
			$message = "Thank-You! Product Added Successfully.";
			
			$output['status']			= 'success';
			$output['success_msg']		= $message;
			$output['msg']				= $message;
			$output['msgHead']			= "Success ! ";
			$output['msgType']			= "success";
			$output['success']			= true;
			$output['slideToTop']		= true;
			$output['url']		        = URL::to('/sellerpanel/product');
			
			
			
			
			//~ if($id!=null) 
			//~ $output['url']				= route('seller.product.edit',['id'=>$id]);
			//~ else
			//~ $output['url']				= route('seller.product.edit',['id'=>$pro->id]);
			
			return response()->json($output);
		}
	
	   
	
		
    }




public function storePriceVar(valRquest $request, $id=null)
	{
		//echo "done";
			$rules['attr_id']		= "required";
			$rules['attr_value'] 	= "required";
			$rules['extra_price']	= "required";
			$rules['display_order']	= "required";
			
			$errorMsg				= "Opps ! Please fill required fields.";
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
			}
			else{
				
			$input=$request->all();
			
			$pro = ProductAttributes::updateOrCreate(['id' => $id], $input);
			
			if($id!=null)
			$message = "Thank-You! Price Variation Updates Successfully.";
			else
			$message = "Thank-You! Price Variation Added Successfully.";
			
			$output['status']			= 'success';
			$output['msg']				= $message;
			$output['msgHead']			= "Success ! ";
			$output['msgType']			= "success";
			$output['success']			= true;
			$output['slideToTop']		= true;
			$output['resetform']		= true;
			$output['url']				= route('seller.product.edit',$input['product_id']).'?tab=attrTable';
			$output['reloadById']		= 'attrTable';
			
			return response()->json($output);
		}
	}
	
	
		public function getModalDeletePriVar($id = null)
    {
	
		$model = 'Product';
		$confirm_route = $error = null;
		
		
		$store = Products::where('seller_id', $this->userId)->where('id',$id)->first();
		
		//$store= Products::where('id', $id)->first();
		
		
		
		if (empty($store)) {
			return Redirect::route('sellerdashboard');
		}
		else
		{
			$confirm_route = route('sellerpanel/delete/product', ['id' => $id]);
			return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
		}
    }
    
    
    public function destroyPriVar($id)
	{
		
		
		$store =  Products::where('id', $id)
					->where('seller_id', $this->userId)
					->update(['status' => 'Inactive']);	 
		
		
		
		$message="Success! Attribute Deleted Successfully";
		$notification = array(
			
			'message' =>  $message, 
			'alert-type' => 'success'
			);
		
		return redirect('/sellerpanel/product')->with($notification);
		
	}
	
	
	
	
	public function storeImages(valRequest $request, $id)
	{
		//echo "done"; die;
			$rules['product_images']		= "required";
			
			$errorMsg				= "Opps ! Please fill required fields.";
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
			}
			else{
				
			$input=$request->all();
			
			$file = $request->product_images;
			//$records= Marketplace::Select('item_image')->where('id',$id)->first();   	
			
			if(!empty($input['product_images'][0]))
			{
					//$images = array();
					foreach($file as $key=>$file){		
						$extension = $file->extension();
						$folderName = '/products/'.$id;
						$safeName = str_random(10) . '.' . $extension;
						@mkdir($folderName,0777,true);
						@chmod($folderName,0777);
						Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
						//$images[]=$safeName;
						
						$img = new ProductImages();
						$img->image = $safeName;
						$img->product_id = $id;
						$img->save();
					}				
			}
			
			$message = "Thank-You! Image Added Successfully.";
			
			$output['status']			= 'success';
			$output['msg']				= $message;
			$output['msgHead']			= "Success ! ";
			$output['msgType']			= "success";
			$output['success']			= true;
			$output['slideToTop']		= true;
			$output['resetform']		= true;
			$output['selfReload']		= true;
			
			return response()->json($output);
		}
	}
	
	public function delete_image($id)
	{
		//echo "done"; die;
		$image = ProductImages::find($id);
		
		$folderName = '/products/'.$image->product_id;
		$filedir = $folderName .'/'. $image->image;
		Storage::disk('uploads')->delete($filedir);
		
		$image->forceDelete();
		return response()->json(['status'=>'success']);
	}
	

    public function productlist($type=null)
      {
       
         //~ Cache::flush();	 
	   //~ Artisan::call('config:cache');
	   
	   
		$approve =  $this->checkApprove();
		
		
		if($approve == false){
			return Redirect::route('sellerdashboard')->with('error', Lang::get('You seller profile is not approved yet, after necessary observation we will make it active,  it may take 24-48 hours'));
		}

		$products = Products::select('*',DB::raw("(DATE_FORMAT(`tbl_products`.created_at,'%d %M  %Y')) as add_date"))
		->where('products.status','Active')
		->orderBy('id',"DESC")
		->where('seller_id',$this->userId)->get();

		


		 return view('vendor.product.productlist',compact('products'));

	  }

	public function autoApproval()
	{
		
	     $seller =  SellerDetails::where('auto_approve',"Yes")
	                           ->where('user_id',$this->userId)->first();
		if(empty($seller))
		return false;
		else
		return true;                       
	}
	


}



