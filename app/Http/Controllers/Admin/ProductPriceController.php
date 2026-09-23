<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\ProductPrice;
use App\Products;
use Redirect;
use Sentinel;
use Session;
use View;
use DB;
use Datatables;
use Input;
use Response;
use Validator;
use Storage;
use App\Helpers\datehelper;

class ProductPriceController extends CodespurController
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
		$PARENT_ID= 28;
		$totalRecord	= ProductPrice::count();
		return view('admin.product-price.list',compact('PARENT_ID','totalRecord'));
	}
	//=============== All List Data Function ==========================//
	public function data()
	{
		
		 $data = ProductPrice::select('product_prices.id', 'product_prices.price','product_prices.currency', 'country.cntry_name', 'products.product_title', 'product_prices.alphacode', 'product_prices.symbol',
		 		 DB::raw("(DATE_FORMAT(`tbl_product_prices`.created_at,'%d %M  %Y')) as add_date"))
		 ->join('country', 'country.cntry_id', 'product_prices.country')
		 ->join('products', 'products.id', 'product_prices.product_id')
		 ->get();
		 return Datatables::of($data)
            ->addColumn('actions', '<a title="View Info" class="btn btn-success btn-xs purple" href="{{URL::to("admin/product-price/show/$id")}}"data-toggle="modal" data-target="#modal-email"><i class="fa fa-eye"></i>
				</a>
				<a class="delval btn btn-xs btn-primary" title="Edit Record" href="{{URL::to("admin/product-price/edit/$id")}}"><i class="fa fa-edit"></i>
				</a>
				<a data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/product-price/$id/confirm-delete")}}" class="delval btn btn-xs btn-danger"  title="Delete Record">
					<i class="fa fa-trash"></i>
				</a>
              ')
			->rawColumns(['actions'])
            ->make(true);
		
	}

	public function create($id=null)
	{
		$products = Products::where('deleted_at', null)->orderby('id', 'desc')->get();
		$PARENT_ID=28;
		$data	= ProductPrice::find($id);
		return view('admin.product-price.edit',compact('PARENT_ID','data', 'products'));
	}

	public function store(Request $request,$id=null)
	{
		//echo "fmdk"; die;
		$this->validate($request,
						[
							'product_id' => 'required|numeric',
							'country' => 'required',
							'price' => 'required | numeric',
							'symbol' => 'required',
							'alphacode' => 'required',
							'currency' => 'required',
						], 
						[
							'product_id.required' => 'Product name field can not be blank',
							'product_id.numeric' => 'Product name field can not be blank',
							'country.required' => 'Country name field can not be blank',

						]
					);
		
		$input = $request->all();
		if($id!=null)
		 $message=" Product Price Updated Successfully";
		else
		 $message="Product Price Added Successfully";
		ProductPrice::updateOrCreate(['id' => $id], $input);
		
		$notification = array(
			'message' =>  $message, 
			'alert-type' => 'success'
			);
			
			return redirect('admin/product-price')->with($notification);
	}

	public function view($id)
	{
		$PARENT_ID=28;

		$detail  = ProductPrice::select('product_prices.id as id', 'product_prices.price','product_prices.currency', 
					'country.cntry_name', 'products.product_title','product_prices.alphacode', 'product_prices.symbol',
	 		 		DB::raw("(DATE_FORMAT(`tbl_product_prices`.created_at,'%d %M  %Y')) as add_date"),
	 		 	    DB::raw("(DATE_FORMAT(`tbl_product_prices`.created_at,'%d %M  %Y')) as update_date"))
				 ->join('country', 'country.cntry_id', 'product_prices.country')
				 ->join('products', 'products.id', 'product_prices.product_id')
				 ->first();

		if(count($detail)==0)
		{
		 $notification = array(
			'message' =>  'Sorry Product Price Data Not Found.', 
			'alert-type' => 'warning'
			);
			return redirect('admin/product-price')->with($notification);
		}
        
		return view('admin.product-price.view',compact('PARENT_ID','detail'));
	}
	
	public function getModalDelete($id = null)
    {
	
		$model = 'Product Price';
		$confirm_route = $error = null;
		$data = ProductPrice::where('id', $id)->first();
		if (empty($data)) 
		{
			$error = "Sorry ! Data Not Found !.";
		}
		else
		{
			$confirm_route = route('delete/product-price', ['id' => $id]);
			return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
		}
    }




	public function destroy($id)
	{
		$edu = ProductPrice::find($id);
		$res=$edu->delete();

		$notification = array(
			'message' =>  'Record deleted successfully !.', 
			'alert-type' => 'success'
			);
		return Redirect::route('admin.product-price')->with($notification);
	}

 }
