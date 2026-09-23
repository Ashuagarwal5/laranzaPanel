<?php
namespace App\Http\Controllers;
use App\Country;
use App\EmailTemplate;
use App\Helpers\datehelper;
use App\Helpers\Thumbnail;
use App\Logs;
use App\MyAddress;
use App\Order;
use App\OrderItem;
use App\State;
use App\States;
use App\User;
use App\UserReview;
use App\UsersInvestments;
use App\WebsiteSetting;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use DB;
use Hash;
use Illuminate\Http\Request;
use Mail;
use Redirect;
use Response;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Session;
use URL;
use Validator;
use View;

class UserController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	function __construct() {
		$this->date = datehelper::dateformat();
		$this->user = Sentinel::getUser();
	}
	public function login() {
		if (Sentinel::check() && Sentinel::inRole('user')) {
			return Redirect::route('dashboard');
		}
		return View('page.sign_in');
	}
	public function postLogin(Request $request) {
		//$rules['mobileno']                = "required|numeric|digits:10";
		$rules['mobileno'] = "required";
		$rules['password'] = "required|string";
		$errorMsg = "Oops ! Please fill required fields.";
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
		}
		if (is_numeric($request->mobileno)) {
			$result = User::where('mobileno', $request->mobileno)
				->where('deleted_at', null)->first();
		} else {
			$result = User::where('email', $request->mobileno)
				->where('deleted_at', null)->first();
		}
		//echo $result; die;
		try
		{
			//login user based on email or mobile number
			//Sentinel::authenticate($with_mob, false, true) ||
			if (is_numeric($request->mobileno)) {
				if (isset($result)) {
					$mobile = $result->email;
					$password = $request->get('password');
				}
			} else {
				$mobile = $request->get('mobileno');
				$password = $request->get('password');
			}
			//$with_mob = array('mobileno'    => $mobile, 'password' => $password);
			$with_email = array('email' => $mobile, 'password' => $password);
			if ($result != null) {
				if (Sentinel::authenticate($with_email, false, true)) {
					if (Sentinel::inRole('user')) {
						$user = Sentinel::getUser();
						$logs = New Logs();
						$logs->browser = $request->header('User-Agent');
						$logs->name = $user->first_name;
						$logs->user_id = $user->id;
						$logs->type = 'User';
						$logs->save();
						date_default_timezone_set('Asia/Kolkata');
						$time = strtotime(date('d M Y  h:i A'));
						//echo $time; die;
						$ip = Thumbnail::getclientip();
						//echo $ip; die;
						$output['loginstatus'] = 'success';
						$output['msg'] = "Thank-You! You are successfully login.";
						$output['msgHead'] = "Success ! ";
						$output['msgType'] = "success";
						$output['status'] = 'success';
						$output['success'] = true;
						$output['slideToTop'] = true;
						$output['success_msg'] = "Thank-You! You are successfully login.";
						$output['slideToTop'] = true;
						$uri = Session::get('uri');
						if (isset($uri)) {
							$output['url'] = $uri;
						} else {
							$output['url'] = route('dashboard');
						}
						return response()->json($output);
					}
				}
			}
			return response()->json(['errorArray' => '', 'error_msg' => "Sorry! Your login details is not correct, please enter correct user details.", 'msgColor' => "Red", 'slideToTop' => 'yes']);
		} catch (NotActivatedException $e) {
			return response()->json(['errorArray' => '', 'error_msg' => "Sorry! Your account not activated.", 'msgColor' => 'Red', 'slideToTop' => 'yes']);
		} catch (ThrottlingException $e) {
			$delay = $e->getDelay();
			return response()->json(['errorArray' => '', 'error_msg' => "Sorry! Your account has been suspended, Please try after " . $delay . " seconds.", 'msgColor' => 'Red', 'slideToTop' => 'yes']);
		}
		// Ooops.. something went wrong
		return back()->withInput()->withErrors($this->messageBag);
	}
	public function dashboard() {
		if (!Sentinel::check()) {
			return Redirect::route('home');
		}
		$userId = Sentinel::getUser()->id;
		$data = User::select('*')->where('id', $userId)->first();
		//get recently orders of current user
		$orders = Order::where([
			['payment_status', '=', 'Paid'],
			['member_id', '=', $userId],
		])
			->orWhere([
				['member_id', '=', $userId],
				['payment_status', '=', 'Pending'],
				['payment_method', '=', 'cod'],
			])
			->orderby('order_id', 'desc')
			->offset(0)
			->limit(5)
			->get();
		$address = MyAddress::select('my_address.*')
			->where('my_address.user_id', $userId)
			->where('my_address.deleted_at', null)
			->get();
		return view('account.dashboard', compact('data', 'orders', 'address'));
	}
	public function register() {
		if (Sentinel::check() && Sentinel::inRole('user')) {
			return Redirect::route('dashboard');
		}
		return view('page.sign_up');
	}
	public function storeRegister(Request $request) {
		$rules['first_name'] = "required|string|min:3|max:255";
		$rules['mobileno'] = "required|numeric|digits:10|unique:users";
		$rules['email'] = "required|string|email|max:255|unique:users";
		$rules['password'] = "required|string|min:6|confirmed";
		$rules['password_confirmation'] = "required|string|min:6";
		$errorMsg = "Oops ! Please fill required fields.";
		$input = $request->all();
		$custom_message = [
			'mobileno.unique' => 'The mobile number  has already been taken.',
			'mobileno.numeric' => 'Enter valid mobile number.',
			'mobileno.digits' => 'Enter valid mobile number.',
		];
		$validator = Validator::make($request->all(), $rules, $custom_message);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
		} else {
			try
			{
				//==================================================//
				$email = $request->get('email');
				$password = $request->get('password');
				//=========== Register User Credentials ============//
				$credentials = [
					'email' => $input['email'],
					'password' => $input['password'],
					'mobileno' => $input['mobileno'],
					'first_name' => $input['first_name'],
					'last_name' => $input['last_name'],
					'otp' => '123456',
				];
				//register user
				$user = Sentinel::registerAndActivate($credentials);
				$new_user_id = $user->id;
				//=========== Find & Attach User Role ==============//
				$role = Sentinel::findRoleByName('User');
				$role->users()->attach($user);
				$login = array('mobileno' => $input['mobileno'],
					'password' => $input['password'],
				);
				if (Sentinel::authenticate($login, false, true)) {
					$output['status'] = 'success';
					$output['msg'] = "Thank-You! You are successfully Registered.";
					$output['msgHead'] = "Success ! ";
					$output['msgType'] = "success";
					$output['success'] = true;
					$output['slideToTop'] = true;
					$output['redirect'] = 'yes';
					$output['redirectUrl'] = route('success-info');
					// $output['redirectUrl']  = route('enter-otp');
					return response()->json($output);
				}
			} catch (UserExistsException $e) {
				return response()->json(['errorArray' => 'User Already Exist.', 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
			}
		}
	}
	public function otp_view() {
		return view('page.enter-otp');
	}
	public function resend_otp() {
		$user_id = session()->get('new_user_id');
		$user = Sentinel::findById($user_id);
		if (!empty($user)) {
			//send passtmp otp 654321
			$otptmp = 654321;
			DB::table('users')
				->where('mobileno', $user->mobile) // find your user by their mobile
				->update(array('otp' => $otptmp));
			$output['status'] = 'success';
			$output['msg'] = "Verification code resend successfully.";
			$output['msgHead'] = "Success ! ";
			$output['msgType'] = "success";
			$output['success'] = true;
			$output['slideToTop'] = true;
			$output['redirect'] = 'not';
			return response()->json($output);
		} else {
			$errorMsg = "User Not Found";
			return response()->json(['error_msg' => $errorMsg, 'slideToTop' => 'yes']);
		}
	}
	public function match_otp(Request $request) {
		$rules['otp'] = "required|numeric|digits:6";
		$errorMsg = "Oops ! Something went wrong.";
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
		}
		//get new user id from session
		$user_id = session()->get('new_user_id');
		$user = Sentinel::findById($user_id);
		// echo "<pre>";
		// print_r($user);
		if (empty($user)) {
			$errorMsg = "User Doesn't Register";
			return response()->json(['errorArray' => 'User Does Not  Exist.', 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
		}
		if ($request->otp == $user->otp) {
			$activation = Activation::create($user);
			$user = Sentinel::findById($user_id);
			if (Activation::exists($user) && Activation::complete($user, $activation->code)) {
				//if everything is fine then erase new useer id from session id
				session()->forget('new_user_id');
				$output['status'] = 'success';
				$output['msg'] = "Thank-You! Mobile Number Verified Successfully.";
				$output['msgHead'] = "Success ! ";
				$output['msgType'] = "success";
				$output['success'] = true;
				$output['slideToTop'] = true;
				$output['redirect'] = 'yes';
				// $output['redirectUrl']   = route('success-info');
				$output['redirectUrl'] = route('success-info');
				return response()->json($output);
			} else {
				$errorMsg = "Your Account Not Activate !";
				return response()->json(['errorArray' => 'Your Account Not Activate  !.', 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
			}
		} else {
			$errorMsg = "Invalid Verification Code";
			return response()->json(['errorArray' => 'Invalid Verification Code.', 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
		}
	}
	public function success_info() {
		return view('page.success-info');
	}
	public function generateotp() {
		$mobile = session::get('user_mobile');
		$otpstring = mt_rand(100000, 999999);
		$user = User::where('mobile', $mobile)->first();
		$input['otp'] = $otpstring;
		// $user = User::updateOrCreate(['id' =>$user->id], $input);
		DB::table('users')
			->where('email', $user->email) // find your user by their email
			->limit(1) // optional - to ensure only one record is updated.
			->update(array('otp' => $otpstring));
		$user_update = User::where('mobile', $mobile)->first();
		$username = config('smssetting.User Name');
		$smspassword = config('smssetting.Password');
		$senderId = config('smssetting.Sender Id');
		$mobileno = $user_update->mobileno;
		$message = 'Dear User, ' . $otpstring . ' is your OTP. Veryfiy your account first using this OTP. then continue on mobileemart.com';
		file_get_contents("http://sms2.tradelitindia.com/sendsms.jsp?user=mobileem&password=ukpoem&senderid=MEMART&mobiles=" . $mobileno . "&sms=" . urlencode($message) . "");
	}
	public function getForgotPassword() {
		if (Sentinel::check() && Sentinel::inRole('user')) {
			return Redirect::route('dashboard');
		}
		return view('page.forget-password');
		// return Redirect::to('https://gnt.helprays.com/signin');
	}
	public function postForgotPassword(Request $request) {
		if (Sentinel::check() && Sentinel::inRole('user')) {
			return Redirect::route('dashboard');
		}
		//forget password using mobile or email
		//$rules['mobileno'] = "required|numeric|digits:10";
		$rules['mobileno'] = "required|email|max:255";
		//custom  essages
		$custom_message = [
			'mobileno.email' => 'Enter valid email id',
		];
		$errorMsg = "Oops ! some error occured.";
		$validator = Validator::make($request->all(), $rules, $custom_message);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
		} else {
			$mobileno = $request->mobileno;
			$User = User::Where('email', $mobileno)
				->first();
			if (count((array) $User) > 0) {
				$tmppassword = str_random(10);
				$user = Sentinel::findById($User->id);
				$passtmp = Hash::make($tmppassword);
				$useremail = $user->email;

				$name = $user->full_name;
				DB::table('users')
					->where('email', $user->email)
					->limit(1)
					->update(array('password' => $passtmp));
				$template = EmailTemplate::Select('em_tm_id', 'title', 'subject', 'message')->where('em_tm_id', 1)->first();
				$sitelogo = '<img src="' . asset(config('constants.frontend.logo')) . '"style="width:100px;" >';

				$siteData = WebsiteSetting::select('goes_from_email', 'contact_email', 'admin_email', 'goes_from_name', 'site_name')->where('id', 1)->first();
				$goes_from_email = $siteData->goes_from_email;
				$adminemail = $siteData->contact_email;
				$siteName = $siteData->site_name;
				$str = str_replace("{#sitelogo}", $sitelogo, $template->message);
				$str = str_replace("{#email}", $useremail, $str);
				$str = str_replace("{#name}", $name, $str);
				$str = str_replace("{#password}", $tmppassword, $str);
				$str = str_replace("{#subject}", 'Registration Sussefully', $str);
				$str = str_replace("{#message}", 'Your Sussefully Register', $str);
				$str = str_replace("{#site_name}", $siteName, $str);
				Mail::send('email.email', compact('str'), function ($m) use ($template, $adminemail, $siteName, $goes_from_email, $useremail) {
					$m->from($goes_from_email, $siteName);
					$m->to($useremail, $siteName);
					$m->subject($template->subject);
				});
				$success_message = "Your new password has been sent on your email address provided.";
				$output['status'] = 'success';
				$output['msg'] = $success_message;
				$output['message'] = $success_message;
				$output['msgHead'] = "Success ! ";
				$output['msgType'] = "success";
				$output['resetform'] = true;
				$output['success'] = true;
				$output['slideToTop'] = true;
				return response()->json($output);
			} else {
				return response()->json(['errorArray' => '', 'error_msg' => "Error! your email id is incorrect", 'msgColor' => "Red", 'slideToTop' => 'yes']);
			}
		}
	}
	public function accountSetting() {
		if (!Sentinel::check()) {
			return Redirect::route('home');
		}
		// echo "<pre>";
		// print_r(Sentinel::getUser()->toArray());
		$countries = Country::select('*')->orderBy('cntry_name', 'asc')->get();
		$userId = Sentinel::getUser()->id;
		$data = User::where('id', $userId)->first();
		return view('account/edit-profile', compact('data', 'countries'));
	}
	public function getLogout(Request $request) {
		//catch all cart items on logout
		$cart = collect($request->session()->get('cart'));
		Sentinel::logout();
		$request->session()->flush();
		if (!config('cart.destroy_on_logout')) {
			$cart->each(function ($rows, $identifier) use ($request) {
				$request->session()->put('cart.' . $identifier, $rows);
			});
		}
		return Redirect::route('home')->with('success', 'You have successfully logged out!');
	}
	public function updateProfile(Request $request, $id) {
		$rules['first_name'] = "required|string";
		$rules['address'] = "required|string";
		$rules['email'] = "required|string";
		$rules['country'] = "required|numeric";
		$rules['state'] = "required|string";
		$rules['city'] = "required|string";
		$errorMsg = "Oops ! Please fill required fields.";
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
		} else {
			$store['first_name'] = $request->first_name;
			$store['last_name'] = isset($request->last_name) ? $request->last_name : '';
			$store['email'] = $request->email;
			$store['full_name'] = $request->first_name . " " . isset($request->last_name) ? $request->last_name : '';
			$store['address'] = $request->address;
			$store['country_id'] = $request->country;
			$store['state_id'] = $request->state;
			$store['city'] = $request->city;
			$updateUsers = User::where('id', $id)->update($store);
			$output['status'] = 'success';
			$output['msg'] = "Your profile has been updated successfully.";
			$output['msgHead'] = "Success ! ";
			$output['msgType'] = "success";
			$output['success'] = true;
			$output['slideToTop'] = true;
			$output['redirect'] = 'yes';
			$output['redirectUrl'] = route('dashboard');
			return response()->json($output);
		}
	}
	public function updatePassword(Request $request) {
		$user = Sentinel::getUser();
		$rules['old_password'] = "required|string|min:6";
		$rules['password'] = "required|string|min:6|confirmed";
		$rules['password_confirmation'] = "required|string|min:6";
		$errorMsg = "Oops ! Please fill required fields.";
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
		} else {
			if (Hash::check($request->old_password, $user->password)) {
				if (Hash::check($request->password, $user->password)) {
					$errorMsg = "Oops ! Old password & New password are same please use diffrent password.";
					return response()->json(['error_msg' => $errorMsg, 'slideToTop' => 'yes']);
				} else {
					$hashedPassword = Hash::make($request->password);
					User::where('id', $user->id)->update(['password' => $hashedPassword]);
					$output['status'] = 'success';
					$output['msg'] = "Password updated successfully.";
					$output['msgHead'] = "Success ! ";
					$output['msgType'] = "success";
					$output['success'] = true;
					$output['slideToTop'] = true;
					$output['redirect'] = 'yes';
					$output['redirectUrl'] = route('dashboard');
					return response()->json($output);
				}
			} else {
				$errorMsg = "Oops ! Old password does not match.";
				return response()->json(['error_msg' => $errorMsg, 'slideToTop' => 'yes']);
			}
		}
	}
	public function my_investments() {
		if (!Sentinel::check()) {
			return Redirect::route('home');
		}
		$userId = Sentinel::getUser()->id;
		$data = User::select('first_name', 'mobile', 'email')->where('id', $userId)->first();
		$invest_data = UsersInvestments::select('users_investments.*', 'projects.project_title')
			->join('projects', 'projects.id', 'users_investments.game_id')
			->where('users_investments.user_id', $userId)
			->get();
		return view('account/my_investments', compact('data', 'invest_data'));
	}
	public function my_orders() {
		if (!Sentinel::check()) {
			return Redirect::route('home');
		}
		$userId = Sentinel::getUser()->id;
		//get all compelete and pending orders of current user
		$c_orders = Order::where([
			['payment_status', '=', 'Paid'],
			['member_id', '=', $userId],
		])
			->orWhere([
				['member_id', '=', $userId],
				['payment_status', '=', 'Pending'],
				['payment_method', '=', 'cod'],
			])
			->orderby('order_id', 'desc')
			->get();
		$p_orders = Order::where('member_id', $userId)
			->where('payment_status', 'Pending')
			->orderby('order_id', 'desc')
			->get();
		return view('account.my-orders', compact('c_orders', 'p_orders'));
	}
	//user's all shipping addresses
	public function my_address() {
		if (!Sentinel::check()) {
			return Redirect::route('home');
		}
		$address = MyAddress::select('my_address.*', 'country.cntry_name as country_name')
			->join('country', 'country.cntry_id', 'my_address.country')
			->where('my_address.user_id', Sentinel::getUser()->id)
			->where('my_address.deleted_at', null)
			->get();
		// echo "<pre>";
		// print_r($address);
		return view('account.my-address', compact('address'));
	}
	//add new address
	public function add_address($address_id = null) {
		$userId = Sentinel::getUser()->id;
		$countries = Country::select('*')->orderBy('cntry_name', 'asc')->get();
		$states = States::get();
		if (!Sentinel::check()) {
			return Redirect::route('home');
		}
		if ($address_id == null) {
			$route = route('store.new-address');
			return view('account.add-address', compact('countries', 'states', 'route'));
		} else {
			$address = MyAddress::where('address_id', $address_id)
				->where('user_id', $userId)
				->first();
			// echo "<pre>";
			// print_r($address);
			$route = route('store.new-address', $address_id);
			return view('account.add-address', compact('countries', 'states', 'address', 'route'));
		}
	}
	//set default address
	public function set_default_address(Request $request) {
		$address_id = $request->address_id;
		$userId = Sentinel::getUser()->id;
		//first update all address then set default address
		MyAddress::where('user_id', $userId)
			->update(['set_as_default' => 0]);
		MyAddress::where('address_id', $address_id)
			->where('user_id', $userId)
			->update(['set_as_default' => 1]);
		$output['status'] = 'success';
		$output['msg'] = "Default address set successfully.";
		$output['msgHead'] = "Success ! ";
		$output['msgType'] = "success";
		$output['success'] = true;
		$output['slideToTop'] = true;
		$output['selfReload'] = true;
		$output['redirect'] = 'No';
		//$output['redirectUrl']      = route('account.my-address');
		return response()->json($output);
	}
	public function store_address(Request $request, $id = null) {
		$rules['first_name'] = "required|string";
		$rules['address'] = "required|string";
		$rules['postal_code'] = "required|numeric";
		$rules['country'] = "required|numeric";
		$rules['state'] = "required|string";
		$rules['city'] = "required|string";
		if (isset($request->mobileno)) {
			$rules['mobileno'] = 'required|regex:/^([0-9\s\-\+\(\)]*)$/';
		}
		$customMessages = [
			'mobileno.regex' => 'mobileno / phone will only contains -, +, (',
		];
		$errorMsg = "Oops ! Please fill required fields.";
		$validator = Validator::make($request->all(), $rules, $customMessages);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
		} else {
			$userId = Sentinel::getUser()->id;
			//check if user user has only one address then make it his default address
			$user_address = MyAddress::where('user_id', $userId)
				->where('deleted_at', null)
				->get();
			if (count((array) $user_address) == 0) {
				$default_address = 1;
			} elseif (count((array) $user_address) == 1 && $id != null) {
				$default_address = 1;
			} else {
				$default_address = 0;
			}
			$address['user_id'] = $userId;
			$address['set_as_default'] = $default_address;
			$address['first_name'] = $request->first_name;
			$address['last_name'] = isset($request->last_name) ? $request->last_name : '';
			$address['address'] = $request->address;
			$address['phone'] = isset($request->mobileno) ? $request->mobileno : '';
			$address['state'] = $request->state;
			$address['city'] = $request->city;
			$address['country'] = $request->country;
			$address['postalcode'] = $request->postal_code;
			MyAddress::updateOrCreate(
				['address_id' => $id],
				$address
			);
			if ($id != null) {
				$message = "Address updated successfully !";
			} else {
				$message = "Your new address added successfully !";
			}
			$output['status'] = 'success';
			$output['msg'] = $message;
			$output['msgHead'] = "Success ! ";
			$output['msgType'] = "success";
			$output['success'] = true;
			$output['slideToTop'] = true;
			$output['redirect'] = 'yes';
			$output['redirectUrl'] = route('account.my-address');
			return response()->json($output);
		}
	}
	public function delete_address($address_id) {
		$userId = Sentinel::getUser()->id;
		MyAddress::where('address_id', $address_id)
			->where('user_id', $userId)
			->forceDelete();
		//if user deletes default address then make user's first address default
		$address = MyAddress::where('user_id', $userId)->get();
		if (count((array) $address) > 1) {
			MyAddress::where('user_id', $userId)
				->first()
				->update(['set_as_default' => 1]);
		}
		return Redirect::route('account.my-address')->with('success', 'Adderss deleted successfully');
	}
	public function invoice() {
		if (!Sentinel::check()) {
			return Redirect::route('home');
		}
		return view('account.invoice');
	}
	//user product review
	public function user_review(Request $request) {
		$rules['title'] = "required";
		$rules['message'] = "required";
		$rules['rating'] = "required|numeric";
		$rules['product_id'] = "required|numeric";
		$errorMsg = "Oops ! Please fill required fields.";
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
		} else {
			if (!Sentinel::check() || !Sentinel::inRole('user')) {
				$errorMsg = 'Please ! login first';
				return response()->json(['errorArray' => array(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
			}
			$user = new UserReview;
			$user->product_id = $request->product_id;
			$user->user_id = Sentinel::getUser()->id;
			$user->title = $request->title;
			$user->message = $request->message;
			$user->rating = $request->rating;
			$user->save();
			$output['status'] = 'success';
			$output['msg'] = "Thanks for your feedback.";
			$output['msgHead'] = "Success ! ";
			$output['msgType'] = "success";
			$output['success'] = true;
			$output['slideToTop'] = true;
			$output['redirect'] = 'no';
			$output['selfReload'] = true;
			return response()->json($output);
		}
	}
	public function print_invoice($order_id = null) {
		$user_id = Sentinel::getUser()->id;
		$order = Order::select('shipment_status', 'discount_amount', 'shipping_amount', 'sub_total', 'order.created_at', 'grand_total', 'order.order_id', 'order.currency_symbol', 'users.first_name', 'users.last_name',
			'member_id', 'country.cntry_name as ship_country', 'order_address.phone',
			'order_address.first_name as ship_name', 'order_address.address as ship_address',
			'order_address.city as ship_city', 'order_address.region as state', 'order_address.pincode',
			'phone', 'order_address.email', 'payment_method'
			, DB::raw("(DATE_FORMAT(tbl_order.created_at,'%d %M  %Y')) as add_date")
		)
			->where('order.order_id', $order_id)
			->where('member_id', $user_id)
			->leftjoin('order_address', 'order_address.ord_adrs_id', 'order.address_id')
			->leftjoin('country', 'country.cntry_id', 'order_address.country_id')
			->join('users', 'users.id', 'order.member_id')
			->orderBy('order.order_id', 'desc')
			->first();
		if (count((array) $order) > 0) {
			$items = OrderItem::select('product_name', 'product_id', 'price', 'quantity_order', 'currency_symbol', 'row_total')
				->where('order_id', $order->order_id)
				->get();
		}
		return View::make('admin.orders.print', compact('order', 'items'));
	}
	public function send_mail_touser($user, $data_arr = null) {
		//=============================email===============================================
		$data = DB::table('website_settings')->select('logo', 'goes_from_email', 'contact_email', 'admin_email', 'goes_from_name', 'site_name')->where('id', 1)->first();
		$path = URL::to(Thumbnail::image("logo/$data->logo", "200", "65", "ffffff"));
		$sitelogo = '<img src="' . $path . '" alt="logo" width="108">';
		$template_data = EmailTemplate::where('em_tm_id', 2)->first();
		$full_name = $user->first_name . ' ' . $user->last_name;
		$goes_from_email = $data->goes_from_email;
		$adminemail = $data->contact_email;
		$siteName = $data->goes_from_name;
		$to_mail = $user->email;
		$str = str_replace("{#sitelogo}", $sitelogo, $template_data->message);
		$str = str_replace("{#name}", $full_name, $str);
		$str = str_replace("{#newpassword}", $data_arr['password'], $str);
		$str = str_replace("{#site_name}", $data->site_name, $str);
		$str = str_replace("{#email}", $user->email, $str);
		$send_mail = Mail::send('email.email', ['str' => $str], function ($m) use ($sitelogo, $goes_from_email, $siteName, $to_mail, $template_data, $full_name) {
			$m->from($goes_from_email, $siteName);
			$m->to($to_mail, $full_name);
			$m->subject($template_data->subject);
		});
		//=============================end email===========================================
	}
}