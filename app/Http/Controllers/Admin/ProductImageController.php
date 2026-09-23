<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use View;
use App\ProductImages;
use App\WebsiteSetting;
use App\Products;
use Validator;
use DB;
use Datatables;
use Illuminate\Support\Facades\Storage;
use Redirect;
use App\Helpers\datehelper;

class ProductImageController extends Controller
{

    function __construct()
	{
	  // // $this->date=datehelper::dateformat();
		 // $this->date_format   = WebsiteSetting::select('dateformat')->first();

	}	
	//================== Admin   View Function ==================//
	public function index()
	{

		$PARENT_ID=90;
		return view('admin.productimg.list',compact('PARENT_ID'));
	}

	 public function data($product_id)
    {

       $data= ProductImages::select(['product_images.*', 'products.product_title as poduct_title',DB::raw("DATE_FORMAT(tbl_product_images.created_at,'%d %M %Y') as add_date")] )
        ->join('products', 'products.id', 'product_images.product_id')
        ->where('product_images.product_id', $product_id)
        ->get();

		 return Datatables::of($data)
		         ->addColumn('actions', '<div class="btn-group">    
					<a class="btn btn-success" href="{{route("edit.productimg", $id)}}" title="Edit"><i class="fa fa-pencil"></i></a>

					<a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/products-images/$id/confirm-delete")}}" title="Delete"><i class="fa fa-trash"></i></a>
		       	     </div>
              		')
       ->rawColumns(['actions'])
       ->make(true);

    /*
     <a class="btn btn-primary" href="{{URL::to("admin/products-images/edit/$id")}}" title="Edit"><i class="fa fa-edit"></i></a>	
    */

    }

	 public function create($product_id)
    {
		$PARENT_ID=90;
		$product = Products::where('id', $product_id)->first();
		$route=route('store.productimg');
   		return View('admin.productimg.create',compact('route','PARENT_ID', 'product', 'product_id'));
    }	

    public function edit($ID)
    {
		$PARENT_ID=90;
		$detail = ProductImages::where('id', $ID)->first();
		$product = Products::where('id', $detail->product_id)->first();
		$route=route('updated.productimg', ['id' => $ID]);
        
        $product_id = $detail->product_id;
		return View('admin.productimg.create',compact('detail','route','PARENT_ID', 'product', 'product_id'));
    }

    public function store(Request $request,$ID=NULL)
    {
		$input=$request->all();	
	    $rules['product_id'] = 'required|numeric';
	    $rules['display_order'] = 'numeric';

		$records= ProductImages::where('id',$ID)->first();

		if($ID!=null)
		{
			if(isset($input['image']))
			$rules['image'] = 'required|mimes:jpg,jpeg,png';
		}
		else
		{
			$rules['image'] = 'required|mimes:jpg,jpeg,png';
		}	
		$errorMsg="Opps ! Some Error Occured. Please Try Again.";
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {

			return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);

		}	
		
		if ($file = $request->file('image'))
		{
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $folderName = '/productimg';
            $safeName = str_random(10) . '.' . $extension;
            Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
            $input['image'] = $safeName;
			 if(count($records)>0 && ($records->image!='') && ($input['image']!=''))
            {
				$folderName = '/productimg';
				$filedir = $folderName.'/'.$records->image;
                Storage::disk('uploads')->delete($filedir);
            }
		}
		else
		{
			$input['image'] = $records->image;
		}		

		if($ID!='')
		{
			$data=ProductImages::find($ID);
			if(count($data)==0){
			$messgae="This is not valid action, data not found.";
			return redirect('admin.productimg')->with('error', trans($messgae));
			}			
		}
        
        $input['display_order'] = $request->display_order;
		$data = ProductImages::updateOrCreate(['id' => $ID],$input);
		if($ID!=NULL)
			$messgae="Product Image Updated Successfully";
 		else
			$messgae="Product Image Added Successfully";

		if ($data->save()) 
		{
			//return Redirect::route('products.images', $data->product_id)->with('success', $messgae);
			$output['status']			= 'success';
			$output['success_msg']		= $messgae;
			$output['msg']				= $messgae;
			$output['msgHead']			= "Success ! ";
			$output['msgType']			= "success";
			$output['success']			= true;
			$output['slideToTop']		= true;
			$output['url']				= route('products.images', $data->product_id);
			echo json_encode($output);
			} else {

				return Redirect::route('products.images', $gallery->product_id)
				->with('error', trans('event/message.error.create'));
			}		

    }	
	public function show($ID)
    {
    	$date_format  =  $this->date_format->dateformat ;

	$detail =  ProductImages::Select('id','image','type','title','vedio','ip'
	,DB::raw("DATE_FORMAT(created_at,'$date_format') as add_date")
	,DB::raw("DATE_FORMAT(updated_at,'$date_format') as update_date"))->where('id',$ID)->first();

			return View('admin.productimg.show', compact('detail'));

	 }

	 public function getModalDelete($id =NULL)
	 {
	 	$model = 'Products Iamages';
		$confirm_route = $error = null;
		$confirm_route = route('deleted.productimg', ['id' => $id]);
		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
	 }

	 public function destroy($id=NULL)
	 {
	 	$gallery= ProductImages::find($id);
	 	if(!empty($gallery->image))
	 	{
			$folderName = '/productimg';
			$filedir = $folderName.'/'.$gallery->image;
            Storage::disk('uploads')->delete($filedir);
		}
	 	ProductImages::where('id', $id)->forceDelete();
 		$success ="Image Deleted Succesfully";
 		return Redirect::route('products.images', $gallery->product_id)->with('success', $success);
	 }
}
