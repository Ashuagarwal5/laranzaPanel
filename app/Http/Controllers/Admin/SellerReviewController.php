<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Products;
use App\SellerReviews;
use Redirect;
use Sentinel;
use Session;
use View;
use DB;
use File;
use Datatables;
use Input;
use Response;
use Validator;
use App\Helpers\datehelper;
use Illuminate\Support\Facades\Storage;

class SellerReviewController extends CodespurController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
	function __construct()
	{
		$this->date=datehelper::dateformat();
	} 
	public function index()
	{	
		$PARENT_ID=28;
		 $data = SellerReviews::select('seller_reviews.id','user_name','seller_reviews.publish','rate','review','seller_details.company_name',
			DB::raw("(DATE_FORMAT(`tbl_seller_reviews`.created_at,'%d %M  %Y')) as add_date"))
			->join('seller_details','seller_details.user_id','seller_reviews.seller_id')
			->orderBy('seller_reviews.id','desc')
			->get();
		return view('admin.seller_reviews.list',compact('PARENT_ID','data'));
	}
	
	
	 public function create($id=null){
		$PARENT_ID=28;
	   	
		$data	= SellerReviews::find($id);		
		return view('admin.product_reviews.edit',compact('PARENT_ID','data'));
	}
	
	public function store(Request $request,$id=null)
	{
		$this->validate($request,[
			        'review'  =>'required',
				]);
				
		$input = $request->all();
			
		if($id!=null)
		$message="Seller Reviews Updated Successfully";
		else
		$message="Seller Reviews Created Successfully";
		SellerReviews::updateOrCreate(['id' => $id],$input);		
		$notification = array(
			'message' =>  $message, 
			'alert-type' => 'success'
			);
		return redirect('admin/product_reviews')->with($notification);
	}
	
	
	public function getModalDelete($id = null)
    {	
		$model = 'Seller Reviews';
		$confirm_route = $error = null;
		$product= SellerReviews::where('id', $id)->first();
		if (empty($product)) {
			return Redirect::route('info');
		}
		else
		{
			$confirm_route = route('confirm-delete/seller_reviews', ['id' => $id]);
			return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
		}
    }

	public function destroy($id)
	{
		$product = SellerReviews::find($id);
		$res=$product->delete();
		return Redirect::route('admin.seller_reviews');
	}
	
	public function publish($id)
	{
		$data = SellerReviews::select('publish')->where('id',$id)->first();
		
		if($data->publish == 'Yes')
		{
			SellerReviews::where('id',$id)->update(['publish'=>'No']);
			
			$status = 'success';
				$message = 'Seller Review unpublished successfully.';

				return( 
				array('status' => $status,
				'publish'=>"No",
				'message'=> $message
				)); 
		}else{
			SellerReviews::where('id',$id)->update(['publish'=>'Yes']);
			
			$status = 'success';
				$message = 'Seller Review published successfully.';

				return( 
				array('status' => $status,
				'publish'=>"Yes",
				'message'=> $message
				)); 
		}
	}
	
	
 }
