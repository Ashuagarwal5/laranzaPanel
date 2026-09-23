<?php namespace App\Http\Controllers\Admin;
use App\ActivityCalendar;
use App\ContactUs;
use App\Newsletter;
use App\Order;
use App\Products;
use App\User;
use App\SendMessage;
use App\Wallet;
use App\CustomerPoints;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Response;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Validator;
use View;

class CodespurController extends Controller {

	/**
	 * Message bag.
	 *
	 * @var Illuminate\Support\MessageBag
	 */
	protected $messageBag = null;
	protected $categorylist = null;

	/**
	 * Initializer.
	 *
	 * @return void
	 */
	public function __construct() {

	}

	/**
	 * Crop Demo
	 */

	public function commingSoon() {
		$PARENT_ID = 400;
		return View('admin/commingsoon', compact('PARENT_ID'));
	}
	public function showHome() {
		if (Sentinel::check()) {
			$role = Sentinel::findRoleById(1);
			if ($role) {

				$user = User::select('roles.slug', 'users.id', 'users.email', 'users.mobileno', 'users.first_name', 'users.last_name')
					->join('role_users', 'role_users.user_id', '=', 'users.id')
					->join('roles', 'roles.id', '=', 'role_users.role_id')
					->where('roles.slug', '!=', 'admin')
					->where('roles.slug', '!=', 'sub-admin')
					->where('roles.slug', 'user')
					->count();
					
					
				$dealers = User::select('roles.slug', 'users.id', 'users.email', 'users.mobileno', 'users.first_name', 'users.last_name')
					->join('role_users', 'role_users.user_id', '=', 'users.id')
					->join('roles', 'roles.id', '=', 'role_users.role_id')
					->where('roles.slug', '!=', 'admin')
					->where('roles.slug', '!=', 'sub-admin')
					->where('roles.slug', '!=', 'user')
					->where('roles.slug', 'dealer')
					->count();
				$total_qrcode = Products::count();
				$total_redeem_point = Products::sum('reward_points');
				$total_earn_point = CustomerPoints::where('transaction_type','Earn')->sum('point');
				$total_redeem_point_earned = CustomerPoints::where('transaction_type','Redeem')->sum('point');
				$balanceTotal = $total_earn_point - $total_redeem_point_earned;
				
				// Redeem $balanceTotal
				$enquries = ContactUs::count();
				$products = Products::count();
				
				$total_orders = 0;

				$new_orders = 0;

				 //$subscribers = Newsletter::where('deleted_at', null)->count();

				 $earnTotal =            Wallet::where('payment','Cr')->sum('amount');
				 $redeemTotal =            Wallet::where('payment','Dr')->sum('amount');
				//  $balanceTotal = $earnTotal - $redeemTotal; 
				

				$activitydata = ActivityCalendar::select('event', 'created_at', 'event_type', 'event_date')->get();
				$activity = json_encode($activitydata->toArray());
				
				return View('admin.index', compact('user', 'enquries', 'products', 'total_orders', 'dealers', 'new_orders', 
				'earnTotal', 'redeemTotal' ,'balanceTotal','total_redeem_point','total_earn_point','total_redeem_point_earned','total_qrcode'));
			} elseif(Sentinel::inRole('sub-admin')){
				return View('admin.index');
			}else{
				return View('admin.login');
			}
			//return View('admin.index');
		} else {
			return Redirect::to('admin/signin')->with('error', 'You must be logged in!');
		}

	}
	public function userChart() {
	
		$date = time();
		$current_date = date('Y-m-d h:i:s', $date);

		$cd = explode('-', $current_date);
		$cd[2] = 1;

		$firstdate = implode('-', $cd);

		$data = User::select(['id', 'created_at'
			, (DB::raw("COUNT(created_at) as count"))
			, DB::raw("DATE_FORMAT(`tbl_users`.created_at,'%d') as add_date"),
		])
			->where('created_at', '>=', $firstdate)
			->orderBy('created_at', 'desc')
			->groupBy(DB::raw("DAYOFYEAR(created_at) < DAYOFYEAR(CURDATE()) , DAYOFYEAR(created_at)"))
			->get();

		return json_encode(array('status' => "success", 'detail' => $data));
	}
	//========= Edit Profile Admin ==========================//
	public function editProfile() {

		if (Sentinel::check()) {
			$user = Sentinel::getUser();
			return View('admin/edit_profile', compact('user'));
		} else {
			return Redirect::to('admin/signin')->with('error', 'You must be logged in!');
		}
	}
	public function storeProfile(Request $request, $type = null) {

		//echo "done"; die;
		$input = $request->all();
		if ($type == 'info') {
			$rules['first_name'] = "required|string|min:3|max:255|";
			$rules['last_name'] = "required|string|max:255";
		} else {

			$rules['password'] = "required|string|min:6|confirmed";
			$rules['password_confirmation'] = "required|string|min:6";
		}

		$msg = "Please fill required fields.";
		$msgHead = "Error !";
		$msgType = "error";
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'msg' => $msg, 'msgHead' => $msgHead, 'msgType' => $msgType, 'slideToTop' => 'yes']);
		} else {

			$user = Sentinel::getUser();
			if ($type == 'info') {

				User::where('id', $user->id)->update(['first_name' => $request->get('first_name')
					, 'last_name' => $request->get('last_name')]);
				$output['msg'] = "Profile Updated Successfully.";
				$output['msgHead'] = "Success ! ";
				$output['msgType'] = "success";
				$output['selfReload'] = true;
				$output['status'] = 'success';

			} else if ($type == 'password') {
				$password = Hash::make($request->get('password'));
				User::where('id', $user->id)->update(['password' => $password]);

				$output['msg'] = "Password Updated Successfully.";
				$output['msgHead'] = "Success ! ";
				$output['msgType'] = "success";
				$output['selfReload'] = true;
				$output['status'] = 'success';
			} else {

				$output['msg'] = "Oops Something went wrong.";
				$output['msgHead'] = "Warning ! ";
				$output['msgType'] = "warning";
				$output['status'] = 'success';
			}

			return json_encode($output);

		}

	}
	//======= Add Activity Function ======================//
	public function addActivity(Request $request) {
		$input = $request->all();
		$activity = new ActivityCalendar();
		$activity->event = $input['event'];
		$activity->event_date = $input['event_date'];
		$activity->event_type = $input['event_type'];
		$activity->slug = str_slug($input['event'] . ' ' . $input['event_type']);
		$activity->save();
		$data = ActivityCalendar::all();
		return json_encode(array('status' => "Success", 'content' => $data));

	}
	public function loadCalendarData() {
		$data = ActivityCalendar::all();
		return json_encode(array('status' => "Success", 'content' => $data));
	}

	public function showView($name = null) {

		if (View::exists('admin/' . $name)) {
			if (Sentinel::check()) {
				return View('admin/' . $name);
			} else {
				return Redirect::to('admin/signin')->with('error', 'You must be logged in!');
			}

		} else {
			return View('admin/404');
		}
	}

	public function showFrontEndView($name = null) {

		if (View::exists($name)) {
			return View($name);
		} else {
			return View('admin/404');
		}
	}

}
