<?php
namespace App\Http\Controllers\vendor;
use App\Http\Controllers\Admin\CodespurController;

use Lang;
use Mail;
use Redirect;
use Sentinel;
use View;
use App\Page;
use App\Users;
use App\SellerReviews;
use DB;
use Datatables;
use App\Product;
use App\Helpers\datehelper;
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
    public function index()
    {

        return view('vendor.reviewlist');
    }

    public function data()
    {

        $review = SellerReviews::leftJoin('users', 'seller_reviews.user_id', '=', 'users.id')
           ->leftJoin('products', 'seller_reviews.product_id', '=', 'products.id')
            ->where('products.seller_id',$this->userId)
           ->select(['products.seller_id','seller_reviews.id','users.full_name','review','rate','products.product_title',DB::raw("(DATE_FORMAT(`tbl_seller_reviews`.created_at,$this->date)) as add_date")])
           ->orderBy('seller_reviews.id', 'desc')
           ->get();
           
         //  echo  "<pre>"; print_r($review); die;
     
      
      
        return Datatables::of($review)
        //    ->add_column('actions', '')
             ->add_column('actions', '
             ')
            ->make(true);
    }



public function viewRecord($rwID)
    {
		$detail=SellerReviews::join('users', 'review.member_id', '=', 'users.id')
		->join('products', 'review.product_id', '=', 'products.id')
		->select(['review.rw_id','users.first_name','review','products.title','rate','review.created_at'])
		->where('review.rw_id',$rwID)
		->where('products.seller_id',$this->userId)
		->first();
        return View('vendor.reviewview',compact('detail'));

     }
	
 	//Delete
    public function getModalDelete($id = null)
    {
		$model = 'Review';
		$confirm_route = $error = null;
		$confirm_route = route('finaldelete/sellerreview', ['id' => $id]);
        return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
    }
     public function permanentDelete($id)
     {

				SellerReviews::
				 join('products', 'review.product_id', '=', 'products.pro_id')
				->where('rw_id',$id)
				->where('products.seller_id',$this->userId)
				->withTrashed()
				->forceDelete();
				$success="Review Deleted Successfully";
				return Redirect::route('seller.review')->with('success', $success);
     }

	
 


}
