<?php
namespace App\Http\Controllers\SalesOfficer;
use App\Helpers\AjaxFormValidator;
use Illuminate\Support\Facades\Hash;


use App\Blog;
use App\BlogComment;
use App\BlogViewer;
use App\ContactUs;
use App\EmailTemplate;
use App\FAQ;
use App\Dealer;
use App\User;
use App\Helpers\Thumbnail;
use App\Mail\EnquiryEmail;
use App\Newsletter;
use App\Order;
use App\OrderItem;
use App\ProductImages;
use App\ProductPrice;
use App\Products;
use App\Promocode;
use App\SociallyConcious;
use App\CustomerPoints;
use App\States;
use App\StaticPage;
use App\Testimonial;
use App\WebsiteSetting;
use Artisan;
use Cache;
use Cart;
use DB;
use Illuminate\Http\Request;
use Mail;
use Redirect;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Session;
use URL;
use Validator;
use Carbon\Carbon;

//use Razorpay\Api\Api;
use View;

class HomeController {

	public function __construct() {
		/*die('<h1>Coming Soon !<h1> <h3>Site is under construction , plesse try after some times</h3>');
		*/

		$this->viewer_ip = Thumbnail::getclientip();
	}

	public function clearCache() {
		Cache::flush();
		Artisan::call('config:cache');
		Artisan::call('optimize');
		Artisan::call('route:clear');
		Artisan::call('view:clear');
		dd("Cache clear");

	}

    public function showHome(Request $request){
		// Get Login User Id
		$user_id = Sentinel::getUser()->id;

		// Get Dealers Count
		$dealers = Dealer::where('sales_officer_id',$user_id)->count();

		// Get Dealers Id In Array
		$users = Dealer::select('user_id')->where('sales_officer_id',$user_id)->pluck('user_id')->toArray();
		
		// Get Dealers, Customers Count
		$data = User::select(['users.*']);
        $data =	$data->leftjoin('role_users', 'role_users.user_id', '=', 'users.id');
        $data =	$data->leftjoin('roles', 'roles.id', '=', 'role_users.role_id');
        $temp = $data->where('roles.slug', '<>', 'user');
        $temp = $temp->whereIn('users.dealer_id', $users);
        $temp = $temp->orderBy('id', 'desc');
        $data = $temp->count();
		// dd($data);
		
		// Get CustomerPoints Count
		$sales = CustomerPoints::whereIn('dealer_id', $users)->where('transaction_type','Earn')->count();

		// Get Sales Sum Of Points
		$transactions = CustomerPoints::whereIn('dealer_id', $users)->where('transaction_type', 'Earn')->sum('point');

		$begin = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $i = new \DateInterval('P1D');
        $period = new \DatePeriod($begin, $i, $end);
        $dates = array();
        $sales_by_date = array();
		array_unshift($dates);
        foreach ($period as $d) {
            $dates[] = $d->format('Y-m-d');
			$new_date = $d->format('Y-m-d');
			$sales_by_date[] = CustomerPoints::whereIn('dealer_id', $users)
			->where('transaction_type','Earn')
			->whereDate('created_at',$new_date)
			->count();
        }
        return view('dashboard.dashboard', compact('dealers','data','sales','transactions','dates','sales_by_date'));
    }

	public function editProfile(Request $request){
		$user_id = Sentinel::getUser()->id;
		$user_data= User::where('id',$user_id)->first();
		// dd($user_data);
        return view('dashboard.edit_profile',compact('user_data'));
    }

	public function myProfile(Request $request){
		$user_id = Sentinel::getUser()->id;
		$user_data= User::where('id',$user_id)->first();
		// dd($user_data);
        return view('dashboard.my_profile',compact('user_data'));
    }

    public function dealers(Request $request){
        $user_id = Sentinel::getUser()->id;
        $dealer = Dealer::where('sales_officer_id',$user_id)->get();
        return view('dashboard.my_dealers',compact('dealer'));
    }

	public function transection(Request $request, $id = null){
		if($id==null){
			$dealer_id 		= $request->dealer_id;
		}
		$dealer_id 		= $request->dealer_id;
		$from = $request->from;
		$to = $request->to;
		$user_id = Sentinel::getUser()->id;
        
		$transaction = Dealer::select('customer_points.point','customer_points.created_at','customer_points.id','customer_points.product_id','customer_points.qr_value','dealers.user_id as dealer_user_id', 'dealers.dealer_name','users.full_name as user_name')
		->leftJoin('customer_points','customer_points.dealer_id','dealers.user_id')
		->leftJoin('users','users.id','customer_points.user_id');
		
		if($dealer_id != null){
			$transaction = $transaction->where('customer_points.dealer_id',$dealer_id);
		}
		if ($from != null) {
			$transaction = $transaction->where('customer_points.created_at','>=',$from.' 00:00:00');
		}
		if ($to != null) {
			$transaction = $transaction->where('customer_points.created_at','<=',$to.' 00:00:00');
		}
		$transaction = $transaction->where('qr_value','!=', null);
		$transaction = $transaction->where('sales_officer_id',$user_id)->get();
		$dealers = Dealer::where('sales_officer_id',$user_id)->get();
		if ($request->from != null) {
			$from = $request->from;
		} else {
			$from = null;
		}
		if ($request->to) {
			$to = $request->to;
		} else {
			$to = null;
		}
		if ($request->dealer_id) {
			$dealer_id = $request->dealer_id;
		} else {
			$dealer_id = null;
		}
        return view('dashboard.my_transection',compact('transaction','dealers','from','to','dealer_id'));
	}

	public function savePassword(Request $request){
		$rules =[
			        'old_password' => 'required',
			        'new_password' => 'min:6|required_with:confirm_password|same:confirm_password',
			        'confirm_password' => 'min:6'
			    ]; 
			  
		
			    $res=AjaxFormValidator::make($request->all(), $rules);
		
			        if($res['status']==='success'){
		
			            $user = Sentinel::check();
			            $password = User::where('id',$user->id)->first();
			            if(!Hash::check($request->old_password, $password->password)){
			                $res['error_msg']='old Password does not match!!!';
			                $res['status']='error';
			                $res['slideToTop']=true;
		
			            }else{
			                $credentials = ['password'=>$request->confirm_password];
			                $data = Sentinel::update($user, $credentials);
			                $res['success_msg']=' Password Updated Successfully !!!';
			                $res['status']='success';
			                $res['slideToTop']=true;
			                $res['selfReload']=true;
			            }
		
		
			        }
				
			    return $res;
    }

    // public function updatePassword(Request $req){
    //     $rules =[
    //         'old_password' => 'required',
    //         'new_password' => 'min:6|required_with:new_password_confirmation|same:new_password_confirmation',
    //         'new_password_confirmation' => 'min:6'
    //     ]; 
    //     $res=AjaxFormValidator::make($req->all(), $rules);
    //         if($res['status']==='success'){
    //             $user = Sentinel::check();
    //             $password = User::where('email',$user->email)->first();
    //             if(!Hash::check($req->old_password, $password->password)){
    //                 $res['error_msg']='old Password does not match!!!';
    //                 $res['status']='error';
    //                 $res['slideToTop']=true;
    //             } else {
    //                 $credentials = ['password'=>$req->new_password_confirmation];
    //                 $data = Sentinel::update($user, $credentials);
    //                 $res['success_msg']=' Password Updated Successfully !!!';
    //                 $res['status']='success';
    //                 $res['slideToTop']=true;
    //                 $res['selfReload']=true;
    //             }
    //         }
    //     return $res;
    // }
}