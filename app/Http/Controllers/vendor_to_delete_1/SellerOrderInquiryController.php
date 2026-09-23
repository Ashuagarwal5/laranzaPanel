<?php
namespace App\Http\Controllers\vendor;
use App\Http\Controllers\CodespurController;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Http\Request;
use Lang;
use Redirect;
use Sentinel;
use View;
use DB;
use Response;
use Datatables;
use Mail;
use App\ProductInquiry;

class SellerOrderInquiryController extends CodespurController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
     var $orderstatus='';

  
	function __construct()
	{
		if (Sentinel::check())
		{ 
			$this->userId = Sentinel::getUser()->id;
		}
	}

    public function index()
    {
		return view('vendor.order.inquirylist');
    }

	public function data()
	{
	
	    $inquiry_data = ProductInquiry::select(DB::raw('DATE_FORMAT(`tbl_product_inquiry`.created_at, "%d  %M %y - %h:%i %p ") as add_date'),'pro_inq_id','name','mobile_no','query','name','email','product_inquiry.quantity','place','products.title')
		->join('products','products.pro_id','=','product_inquiry.product_id')
		->get();
      
        return Datatables::of($inquiry_data)
             ->add_column('actions', '
             <a class="btn default btn-xs purple" data-toggle="modal" data-target="#modal-regular" href="{{ route("seller.orders-inquiry.view",$pro_inq_id) }}">
          <i class="fa fa-eye"></i>
               View
              </a> 
          
             ')
            ->make(true);	
	}
	
	public function viewInnquiry($id)
    {
		$detail=ProductInquiry::
		select(DB::raw('DATE_FORMAT(`tbl_product_inquiry`.created_at, "%d  %M %y - %h:%i %p ") as add_date'),'pro_inq_id','name','mobile_no','query','name','email','product_inquiry.quantity','place','products.title')
		->where('pro_inq_id',$id)
		->join('products','products.pro_id','=','product_inquiry.product_id')
		->where('products.seller_id',$this->userId)
		->first();
        return View('vendor.order.inquiryview',compact('detail'));
     }

	
 
}
