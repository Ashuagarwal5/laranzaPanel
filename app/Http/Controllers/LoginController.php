<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Admin\CodespurController;
use App\Logs;
use App\SellerDetails;
use App\User;
use App\UserService;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use DB;
use Hash;
use Illuminate\Http\Request as valRquest;
use Lang;
use Response;
use Sentinel;
use Session;
use View;
use File;
class LoginController extends CodespurController {
	private $user_activation = true;
	private $userId = null;
	var $redirect_route;
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	function __construct() {
		if (Sentinel::check()) {
			$this->userId = Sentinel::getUser()->id;
		}
	}
	public function login_popup($type) {
		if ($type == 'seller') {
			return view('login.seller_login_popup');
		}
	}
	public function register_popup($type) {
		if ($type == 'seller') {
			return view('login.seller_register_popup');
		}
	}
	public function Download($filename)
	{
		
		$path = storage_path('app/uploads/infobox/'.$filename);
		if (!File::exists($path)) 
		{
			$path = storage_path('app/uploads/infobox/'.$filename);
			if (!File::exists($path))
			{
				return view('404');
			} 
			
		}
		$file = File::get($path);
			$type = File::mimeType($path);
		$response = response()->make($file, 200);
		$response->header("Content-Type", $type);
		return $response;
	}
	public function forgot_password(valRquest $request, $type) {
		$option = $request->get('forgot_option');
		if ($option == 'otp_mobile') {
			$this->validate($request, [
				'mobileno' => 'required|min:8',
				'forgot_option' => 'required',
			]);
			$data_record = User::select('email', 'id', 'mobileno')->where('mobileno', $request->get('mobileno'))->first();
		} else {
			$this->validate($request, [
				'email' => 'required|email',
				'forgot_option' => 'required',
			]);
			$data_record = User::select('email', 'id', 'mobileno')->where('email', $request->get('email'))->first();
		}
		//~ $this->email_mobileno =  $request->get('email_or_mobileno');
		//~ $data_record = User::select('email','id','mobileno')
		//~ ->where(function($query) {
		//~ return $query->orWhere('email', '=',  $this->email_mobileno)
		//~ ->orWhere('mobileno', '=',   $this->email_mobileno);
		//~ })
		//~ ->first();
		$errorMsg = "";
		$success_message = "";
		$failure = false;
		$option = $request->get('forgot_option');
		///  echo  $option; die;
		if (!empty($data_record)) {
			if ($option == 'otp_mobile') {
				$this->generateopt($data_record->mobileno);
				$success_message = "OTP send on your mobileno";
				$redirect_url = route('login');
				session::set('user_mobile', $data_record->mobileno);
				\Session::save();
			} else if ($option == 'tmp_pass_email') {
				$tmppassword = str_random(7);
				$user = Sentinel::findById($data_record->id);
				$passtmp = Hash::make($tmppassword);
				DB::table('users')
					->where('email', $user->email) // find your user by their email
					->limit(1) // optional - to ensure only one record is updated.
					->update(array('password' => $passtmp));
				$success_message = "A temporary password send to your email";
			}
		} else {
			$failure = true;
			$errorMsg = 'user not valid';
		}
		if ($request->ajax()) {
			if ($failure) {
				$data['success'] = false;
				$data['message'] = $errorMsg;
			} else {
				$data['status'] = 'success';
				$data['success'] = true;
				$data['resetform'] = true;
				$data['message'] = $success_message;
				if ($option == 'otp_mobile') {
					$data['callback_type'] = 'forgot_option_main';
					$data['ajaxPageCallBack'] = true;
				}
			}
			$data['slideToThisForm'] = false;
			echo json_encode($data);die;
		}
	}
	public function otp_login(valRquest $request, $type) {
		$unversal_code = config('sitesetting.Universal Code');
		$this->validate($request, [
			'verification_code' => 'required',
		]);
		$success_message = '';
		$errorMsg = '';
		$failure = false;
		if (Sentinel::check()) {
			if ($type == 'site_user') {
				$redirect_url = route('accounts.dashboard');
			} else if ($type == 'seller') {
				$check_seller = UserService::checkService($this->userId);
				if ($check_seller) {
					if ($check_seller->agree_terms == 'Yes') {
						$redirect_url = route('sellerdashboard');
					} else {
						$redirect_url = route('seller.term-condition');
					}

				} else {
					$redirect_url = route('seller.registration');
				}
			}
			$success_message = "Login Successfully";
		}
		$otp = $request->get('verification_code');
		$mobile = session::get('user_mobile');
		$User = User::where('mobileno', $mobile)
			->where('otp', $otp)->first();
		if (!empty($User)) {
			$user = Sentinel::findById($User->id);
			Sentinel::login($user, false);
			\Session::save();
			if ($type == 'site_user') {
				$redirect_url = route('accounts.dashboard');
			} else if ($type == 'seller') {
				$check_seller = UserService::checkService($user->id);
				if ($check_seller) {
					if ($check_seller->agree_terms == 'Yes') {
						$redirect_url = route('sellerdashboard');
					} else {
						$redirect_url = route('seller.term-condition');
					}

				} else {
					$redirect_url = route('seller.registration');
				}
			}
			$success_message = "Login Successfully";
		}
		if (empty($User)) {
			$failure = true;
			$errorMsg = 'Enter Currect Varification Code';
		}
		if ($request->ajax()) {
			if ($failure) {
				$data['success'] = false;
				$data['message'] = $errorMsg;
			} else {
				$data['status'] = 'success';
				$data['success'] = true;
				$data['resetform'] = true;
				$data['message'] = $success_message;
				$data['url'] = $redirect_url;
			}
			$data['slideToThisForm'] = false;
			echo json_encode($data);die;
		}
	}
	public function generateopt($mobile_no) {
		$mobile = $mobile_no;
		$otpstring = mt_rand(100000, 999999);
		$user = User::where('mobileno', $mobile)->first();
		$input['otp'] = $otpstring;
		DB::table('users')
			->where('email', $user->email) // find your user by their email
			->limit(1) // optional - to ensure only one record is updated.
			->update(array('otp' => $otpstring));
		$user_update = User::where('mobileno', $mobile)->first();
	}
	public function register_popup_post(valRquest $request, $type) {
		$this->validate($request, [
			'first_name' => 'required|min:3',
			'last_name' => 'required|min:3',
			'email' => 'required|email|unique:users,email',
			'mobileno' => 'required|numeric|unique:users,mobileno',
			'password' => 'required|between:3,32',
			'password_confirm' => 'required|same:password',
		]);
		$UserData = User::where('email', $request->get('email'))->first();
//echo  "<pre>"; print_r($UserData); die;
		if (empty($UserData)) {
			$register = array(
				'first_name' => $request->get('first_name'),
				'last_name' => $request->get('last_name'),
				'email' => $request->get('email'),
				'mobileno' => $request->get('mobileno'),
				'password' => $request->get('password'),
			);
			$activate = true;
			$user = Sentinel::register($register, $activate);
			//~ $role = Sentinel::findRoleByName('Seller');
			//~ $input =            new RoleUser();
			//~ $input->user_id    = $this->userId;
			//~ $input->role_id    = $role->id;
			//~ $input->save();
			//	$role->users()->attach($user);
			$userdata = Sentinel::findById($user->id);
			Sentinel::login($userdata);
			\Session::save();
			$success_message = "Registration Successfully";
			$failure = false;
			if ($type == 'seller') {
				$redirect_url = route("seller.registration");
			}
		} else {
			$failure = true;
			$errorMsg = "Already have account";
		}
		if ($request->ajax()) {
			if ($failure) {
				$data['success'] = false;
				$data['message'] = $errorMsg;
			} else {
				$data['status'] = 'success';
				$data['success'] = true;
				$data['resetform'] = true;
				$data['message'] = $success_message;
				$data['url'] = $redirect_url;
			}
			$data['slideToThisForm'] = false;
			echo json_encode($data);die;
		}
	}
	public function register_otp_verify(valRquest $request, $type) {
		$unversal_code = config('sitesetting.Universal Code');
		$this->validate($request, [
			'verification_code' => 'required',
		]);
		$tmp_user_id = Session::get('tmp_rg_id');
		$user_code = $request->get('verification_code');
		$errorMsg = "";
		$success_message = "";
		$user_tmp = DB::table('tmp_users')->where('tmp_id', $tmp_user_id)->first();
		$UserData = User::where('email', $user_tmp->email)->first();
		//echo  "<pre>"; print_r($UserData); die;
		if (empty($UserData)) {
			if ($user_code == $user_tmp->otp or $user_code == $unversal_code) {
				$register = array(
					'first_name' => $user_tmp->first_name,
					'last_name' => $user_tmp->last_name,
					'email' => $user_tmp->email,
					'mobileno' => $user_tmp->mobileno,
					'password' => $user_tmp->password,
					'confirm_password' => $user_tmp->password,
				);
				$activate = true;
				$user = Sentinel::register($register, $activate);
				$role = Sentinel::findRoleByName('User');
				$role->users()->attach($user);
				$userdata = Sentinel::findById($user->id);
				Sentinel::login($userdata);
				\Session::save();
				$success_message = "Registration Successfully";
				$failure = false;
				///send email on registration
				if ($type == 'seller') {
					$redirect_url = route("seller.registration");
				} elseif ($type == 'site_user') {
					$redirect_url = route("accounts.dashboard");
				}
				//$tmp_delete = DB::table('tmp_users')->where('tmp_id',$tmp_user_id)->delete();
			} else {
				$failure = true;
				$errorMsg = "Verification Code  Not Matched, Enter Correct Verification Code";
			}
		} else {
			$failure = true;
			$errorMsg = "Already have account";
		}
		if ($request->ajax()) {
			if ($failure) {
				$data['success'] = false;
				$data['message'] = $errorMsg;
			} else {
				$data['status'] = 'success';
				$data['success'] = true;
				$data['resetform'] = true;
				$data['message'] = $success_message;
				$data['url'] = $redirect_url;
			}
			$data['slideToThisForm'] = false;
			echo json_encode($data);die;
		}
	}
	public function login_post123(valRquest $request, $type) {
		$this->validate($request, [
			'email_or_mobileno' => 'required|min:3',
			'password' => 'required',
		]);
		try {
			$this->email_mobileno = $request->get('email_or_mobileno');
			$data_record = User::select('email', 'id', 'mobileno')
				->where(function ($query) {
					return $query->orWhere('email', '=', $this->email_mobileno)
						->orWhere('mobileno', '=', $this->email_mobileno);
				})
				->first();
			$errorMsg = "";
			$success_message = "";
			if (!empty($data_record)) {
				$input_password = $request->get('password');
				$credentials = [
					'email' => $data_record->email,
					'password' => $input_password,
				];
				if (Sentinel::authenticate($credentials, 0)) {
					\Session::save();
					$success_message = "Login Successfully";
					$failure = false;
					if ($type == 'seller') {
						$check_seller = UserService::checkService($data_record->id);
						if ($check_seller) {
							if ($check_seller->agree_terms == 'Yes') {
								$redirect_url = route('sellerdashboard');
							} else {
								$redirect_url = route('seller.term-condition');
							}

						} else {
							$redirect_url = route('seller.registration');
						}
					} elseif ($type == 'site_user') {
						$redirect_url = route("accounts.dashboard");
					}
				} else {
					$failure = true;
					$errorMsg = "Email/Mobile No or password is incorrect.";
				}
			} else {
				$failure = true;
				$errorMsg = "Email/Mobile No or password is incorrect.";
			}
		} catch (UserNotFoundException $e) {
			$failure = true;
			//  $this->messageBag->add('email', Lang::get('auth/message.account_not_found'));
			$errorMsg = Lang::get('auth/message.account_not_found');
		} catch (\Cartalyst\Sentinel\Checkpoints\NotActivatedException $e) {
			$failure = true;
			// $messageBag =     $this->messageBag->add('email', Lang::get('auth/message.account_not_activated'));
			$errorMsg = "Your email is not verified yet, please check your mailbox to verify email";
		} catch (\Cartalyst\Sentinel\Checkpoints\UserSuspendedException $e) {
			$failure = true;
			// $this->messageBag->add('email', Lang::get('auth/message.account_suspended'));
			$messageBag = Lang::get('auth/message.account_suspended');
		} catch (\Cartalyst\Sentinel\Checkpoints\UserBannedException $e) {
			$failure = true;
			$errorMsg = Lang::get('auth/message.account_banned');
			//$this->messageBag->add('email', Lang::get('auth/message.account_banned'));
		} catch (\Cartalyst\Sentinel\Checkpoints\ThrottlingException $e) {
			$failure = true;
			$delay = $e->getDelay();
			// $this->messageBag->add('email', Lang::get('auth/message.account_suspended', compact('delay')));
			$errorMsg = Lang::get('auth/message.account_suspended', compact('delay'));
		}
		if ($request->ajax()) {
			if ($failure) {
				$data['success'] = false;
				$data['message'] = $errorMsg;
			} else {
				$data['status'] = 'success';
				$data['success'] = true;
				$data['resetform'] = true;
				$data['message'] = $success_message;
				$data['url'] = $redirect_url;
			}
			$data['slideToThisForm'] = false;
			echo json_encode($data);die;
		}
	}
	public function login_post(valRquest $request, $type) {

		die("Arpna");
		$this->validate($request, [
			'email_or_mobileno' => 'required|min:3',
			'password' => 'required',
		]);
		try {
			$this->email_mobileno = $request->get('email_or_mobileno');
			$data_record = User::select('email', 'id', 'mobileno')
				->where(function ($query) {
					return $query->orWhere('email', '=', $this->email_mobileno)
						->orWhere('mobileno', '=', $this->email_mobileno);
				})
				->first();
			$errorMsg = "";
			$success_message = "";
			if (!empty($data_record)) {
				$seller_row = SellerDetails::where('user_id', $data_record->id)->first();
				//echo  "<pre>"; print_r($seller_row); die;
				if (!$seller_row) {
					$data['success'] = false;
					$data['message'] = "Not a valid seller account";
					$data['slideToThisForm'] = false;
					echo json_encode($data);die;
				}
				if ($seller_row->status == "Disapprove") {
					$data['success'] = false;
					$data['message'] = "Your Account is not approved yet";
					$data['slideToThisForm'] = false;
					echo json_encode($data);die;
				}
				if ($seller_row->status == "Rejected") {
					$data['success'] = false;
					$data['message'] = "Your Account has been Rejected";
					$data['slideToThisForm'] = false;
					echo json_encode($data);die;
				}
				if ($seller_row->status == "Inactive") {
					$data['success'] = false;
					$data['message'] = "Your Account has been Deleted";
					$data['slideToThisForm'] = false;
					echo json_encode($data);die;
				}
				$input_password = $request->get('password');
				$credentials = [
					'email' => $data_record->email,
					'password' => $input_password,
				];
				if (Sentinel::authenticate($credentials, 0)) {
					\Session::save();
					$success_message = "Login Successfully";
					$failure = false;
					$user = Sentinel::getUser();
					//echo  "<pre>"; print_r($user); die;
					$logs = New Logs();
					$logs->browser = $request->header('User-Agent');
					$logs->name = $seller_row->first_name;
					$logs->user_id = $user->id;
					$logs->type = 'Seller';
					//echo $logs; die;
					$logs->save();
					$redirect_url = route('sellerdashboard');
				} else {
					$failure = true;
					$errorMsg = "Email/Mobile No or password is incorrect.";
				}
			} else {
				$failure = true;
				$errorMsg = "Email/Mobile No or password is incorrect.";
			}
		} catch (UserNotFoundException $e) {
			$failure = true;
			//  $this->messageBag->add('email', Lang::get('auth/message.account_not_found'));
			$errorMsg = Lang::get('auth/message.account_not_found');
		} catch (\Cartalyst\Sentinel\Checkpoints\NotActivatedException $e) {
			$failure = true;
			// $messageBag =     $this->messageBag->add('email', Lang::get('auth/message.account_not_activated'));
			$errorMsg = "Your email is not verified yet, please check your mailbox to verify email";
		} catch (\Cartalyst\Sentinel\Checkpoints\UserSuspendedException $e) {
			$failure = true;
			// $this->messageBag->add('email', Lang::get('auth/message.account_suspended'));
			$messageBag = Lang::get('auth/message.account_suspended');
		} catch (\Cartalyst\Sentinel\Checkpoints\UserBannedException $e) {
			$failure = true;
			$errorMsg = Lang::get('auth/message.account_banned');
			//$this->messageBag->add('email', Lang::get('auth/message.account_banned'));
		} catch (\Cartalyst\Sentinel\Checkpoints\ThrottlingException $e) {
			$failure = true;
			$delay = $e->getDelay();
			// $this->messageBag->add('email', Lang::get('auth/message.account_suspended', compact('delay')));
			$errorMsg = Lang::get('auth/message.account_suspended', compact('delay'));
		}
		if ($request->ajax()) {
			if ($failure) {
				$data['success'] = false;
				$data['message'] = $errorMsg;
			} else {
				$data['status'] = 'success';
				$data['success'] = true;
				$data['resetform'] = true;
				$data['message'] = $success_message;
				$data['url'] = $redirect_url;
			}
			$data['slideToThisForm'] = false;
			echo json_encode($data);die;
		}
	}
}
