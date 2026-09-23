<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Redirect;
use App\Wishlist;
use App\Products;
use View;
use DB;
use Datatables;
use Input;
use Response;
use Validator;
use Storage;
use App\Helpers\datehelper;

class WishlistController extends CodespurController
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
		$PARENT_ID=58;
		$totalRecord	= Wishlist::count();
		
		$data = Products::select('products.product_title','products.id'
			,(DB::raw("COUNT(tbl_wishlist.id) as total_wishlist"))
			)
			->join('wishlist','wishlist.product_id','products.id')
			->groupBy('products.id')
			->get();
		
		return view('admin.wishlist.list',compact('PARENT_ID','totalRecord','data'));
	}
	//=============== All List Data Function ==========================//
	public function data(){

		$data = Wishlist::join('users','wishlist.user_id','=','users.id')
        ->join('products', 'wishlist.product_id', '=', 'products.id')
        ->select(['wishlist.id','products.product_title','users.first_name'
        ,DB::raw("(DATE_FORMAT(`tbl_wishlist`.created_at,'%d %M  %Y')) as add_date")])->orderBy('id', 'desc')->get();
        //echo "<pre>"; print_r($data); die;
		 return Datatables::of($data)
            ->addColumn('actions', '<a title="View Info" class="btn btn-success btn-xs purple" href="{{URL::to("admin/wishlist/show/$id")}}"data-toggle="modal" data-target="#modal-email">
				<i class="fa fa-eye"></i> View
				</a>
				<a data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/wishlist/$id/confirm-delete")}}" class="delval btn btn-xs btn-danger"  title="Delete Enquiry">
				<i class="fa fa-trash"></i> Delete
				</a>
              ')
			->rawColumns(['actions'])
            ->make(true);	
	}
	public function view($id){		
		$PARENT_ID=58;
		$detail = Wishlist::join('users','wishlist.user_id','=','users.id')
        ->join('products', 'wishlist.product_id', '=', 'products.id')
        ->select(['wishlist.id','products.product_title','users.full_name'
        ,DB::raw("DATE_FORMAT(`tbl_wishlist`.created_at,'%M %d, %Y') as enquiry_date")])
        ->orderBy('id', 'desc')
        ->where('product_id',$id)
        ->get();	
        	
		//~ $detail=Wishlist::select(['*'
		//~ ,DB::raw("DATE_FORMAT(`tbl_wishlist`.created_at,'%M %d, %Y') as enquiry_date")])->where('id',$id)->first();
		if(count($detail)==0){
		$notification = array(
			'message' =>  'Sorry Enquiry Not Found.', 
			'alert-type' => 'warning'
			);
			return redirect('admin/wishlist')->with($notification);
		}
		
		$pro_name = Products::where('id',$id)->first()->product_title;
		
		return view('admin.wishlist.user_wishlist',compact('PARENT_ID','detail','pro_name'));
	}
	
	public function getModalDelete($id = null)
    {	
		$model = 'Wishlist';
		$confirm_route = $error = null;
		$wishlist= Wishlist::where('id', $id)->first();
		if (empty($wishlist)) {
			return Redirect::route('info');
		}
		else
		{
			$confirm_route = route('delete/wishlist', ['id' => $id]);
			return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
		}
    }
    
	public function destroy($id)
	{
		
		$wishlist = Wishlist::find($id);
		$res=$Wishlist->delete();
		return Redirect::route('admin.wishlist');
	}

 }
