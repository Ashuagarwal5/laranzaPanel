<?php namespace App\Http\Controllers\Admin;

use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use App\Logs;
use App\EmailTemplate;
use App\SiteSettings;
use Mail;
use Lang;
use Redirect;
use Reminder;
// use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use URL;
use Validator;
use View;
use App\User;
use Session;
use Input;
use Hash;
use DB;
use Cookie;
use Artisan;
class AdminAuthController extends Controller
{
    /**
     * Account sign in.
     *
     * @return View
     */
    public function getSignin()
    {
		
		//~ $user = Sentinel::findById(1);
			//~ Sentinel::login($user, false); 
			
			
		//Artisan::call('config:cache');
		//echo "done"; die;
        // Is the user logged in?
        if (Sentinel::check()) {
            return Redirect::route('admin.dashboard');
        }

        // Show the page
        return View('admin.login');
    }

    /**
     * Account sign in form processing.
     * @param Request $request
     * @return Redirect
     */
    public function postSignin(Request $request)
    {

        try {         
			$credentials = [
                'email'    => $request->email,
                'password' => $request->password,
            ];
            $data = User::where('email',$request->email)->first();
            if ($data) {
                if (Hash::check($request->password, $data->password)) {
                    $user = Sentinel::findById($data->id);
                    Sentinel::login($user);
                    // Start the admin idle-timeout clock (checked in the SentinelAdmin middleware).
                    session(['admin_last_activity' => time()]);
            //     }
            // }
            // if (Sentinel::authenticate($credentials))
            // if (Sentinel::validateCredentials($user, $credentials))
            // {
				// if(Sentinel::inRole('admin') || Sentinel::inRole('sub-admin') )
                // {
					$user 			= Sentinel::getUser();
					$logs 			= New Logs();
					$logs->browser	= $request->header('User-Agent');
					$logs->name		= $user->first_name." ".$user->last_name;
					$logs->user_id	= $user->id;
					$logs->type		= 'Admin';
					// echo "<pre>";print_r($logs); die;
					$logs->save();
					
					$output['status']		= 'success';
					$output['success']		= true;
					$output['slideToTop']	= true;
					$output['success_msg']	= "Thank-You! You are successfully login.";
					$output['slideToTop']	= true;
					$output['url']			= route('admin.dashboard');
					return response()->json($output);
					
				}
                return response()->json(['errorArray'=>'','error_msg'=>"Sorry You are not a Admin for login.",'msgColor'=>"Red",'slideToTop'=>'yes']);
            }
			return response()->json(['errorArray'=>'','error_msg'=>"Sorry! Your login details is not correct, please enter correct email and password.",'msgColor'=>"Red",'slideToTop'=>'yes']);
         
        } catch (NotActivatedException $e) {
			return response()->json(['errorArray'=>'','error_msg'=>"Sorry! Your account not activated.", 'msgColor'=>'Red','slideToTop'=>'yes']);
        } catch (ThrottlingException $e) {
            $delay = $e->getDelay();
            return response()->json(['errorArray'=>'','error_msg'=>"Sorry! Your account has been suspended, Please try after ".$delay." seconds.", 'msgColor'=>'Red', 'slideToTop'=>'yes']);
        }

        // Ooops.. something went wrong
        return back()->withInput()->withErrors($this->messageBag);
    }

    /**
     * Account sign up form processing.
     *
     * @return Redirect
     */
    public function postSignup(Request $request)
    {
        // Declare the rules for the form validation
        $rules = array(
            'first_name'       => 'required|min:3',
            'last_name'        => 'required|min:3',
            'email'            => 'required|email|unique:users',
            'email_confirm'    => 'required|email|same:email',
            'password'         => 'required|between:3,32',
            'password_confirm' => 'required|same:password',
        );

        // Create a new validator instance from our validation rules
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            // Ooops.. something went wrong
            return Redirect::to(URL::previous() . '#toregister')->withInput()->withErrors($validator);
        }

        try {
            // Register the user
            $user = Sentinel::registerAndActivate(array(
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            ));

            //add user to 'User' group
            $role = Sentinel::findRoleById(2);
            $role->users()->attach($user);



            // login user automatically



            // Log the user in
            Sentinel::login($user, false);

            // Redirect to the home page with success menu
            return Redirect::route("dashboard")->with('success', Lang::get('auth/message.signup.success'));

        } catch (UserExistsException $e) {
            $this->messageBag->add('email', Lang::get('auth/message.account_already_exists'));
        }

        // Ooops.. something went wrong
        return Redirect::back()->withInput()->withErrors($this->messageBag);
    }

    /**
     * User account activation page.
     *
     * @param number $userId
     * @param string $activationCode
     * @return
     */
    public function getActivate($userId,$activationCode = null)
    {

        // Is user logged in?
        if (Sentinel::check()) {

            return Redirect::route('dashboard');

        }

        $user = Sentinel::findById($userId);

        $activation = Activation::create($user);

        if (Activation::complete($user, $activation->code))
        {
            // Activation was successful
            // Redirect to the login page
            return Redirect::route('signin')->with('success', Lang::get('auth/message.activate.success'));
        }
        else
        {
            // Activation not found or not completed.
            $error = Lang::get('auth/message.activate.error');
            return Redirect::route('signin')->with('error', $error);
        }

    }


    public function getActivateUser($userId,$activationCode = null)

    {

        // Is user logged in?
        if (Sentinel::check()) {

            return Redirect::route('accounts.dashboard');

        }

        $user = Sentinel::findById($userId);


         $activationrecord = DB::table('activations')->where('user_id',$userId)->where('completed',"1")->get();

       if(count($activationrecord)<1){


        $activation = Activation::create($user);

        if (Activation::complete($user, $activation->code))
        {
            // Activation was successful
            // Redirect to the login page
            return Redirect::route('info')->with('success', Lang::get('Your account has been successfully activated. Please login to continue..'));
        }
        else
        {
            // Activation not found or not completed.
            $error = Lang::get('auth/message.activate.error');
            return Redirect::route('info')->with('error', $error);
        }
	  }
	  else{


		   \Session::flash('info_message', 'Unauthorized: Access is denied');
		  return Redirect::route('info');

		  }


    }


    /**
     * Forgot password form processing page.
     * @param Request $request
     *
     * @return Redirect
     */
    public function postForgotPassword(Request $request)
    {

		$input=$request->all();
		
		$SiteSettings	= SiteSettings::find(1);
		//$SiteSettings->contact_email;
		
		
		
        // Declare the rules for the validator
        $rules = array(
            'forgot_email' => 'required|email',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
           return response()->json(['errorArray'=>$validator,'error_msg'=>"Please enter email id."]);
        }

        try {
            
            $user = Sentinel::findByCredentials(['email' => $request->get('forgot_email')]);
			if (!$user) {
                return response()->json(['errorArray'=>'','error_msg'=>"Sorry! Your email id is not correct, please enter correct email.",'msgColor'=>"Red",'slideToTop'=>'yes']);
            }
            $activation = Activation::completed($user);
            if(!$activation){
                return response()->json(['errorArray'=>'','error_msg'=>"Sorry! Your account is not activated.",'msgColor'=>"Red",'slideToTop'=>'yes']);
            }
				$this->forgotPasswordConfirm($request->get('forgot_email'));
				return response()->json(['errorArray'=>'','success_msg'=>"Success! Your new password successfully send on your email id.",'slideToTop'=>'yes','status'=>'success']);
         
        } catch (UserNotFoundException $e) {
            // Even though the email was not found, we will pretend
            // we have sent the password reset code through email,
            // this is a security measure against hackers.
        }

        //  Redirect to the forgot password
        return Redirect::to(URL::previous() . '#toforgot')->with('success', Lang::get('auth/message.forgot-password.success'));
    }

    /**
     * Forgot Password Confirmation page.
     *
     * @param number $userId
     * @param  string $passwordResetCode
     * @return View
     */

      public function forgotPasswordConfirm($useremail)
     {
		$useremail = $useremail;
		$User= User::where('email',$useremail)->first();
		$tmppassword = str_random(7);

		if (!empty($User)) {

		$user = Sentinel::findById($User->id);
		$passtmp = Hash::make($tmppassword);
		
		DB::table('users')
		->where('email', $user->email)  // find your user by their email
		->limit(1)  // optional - to ensure only one record is updated.
		->update(array('password' => $passtmp));
		
		//==================== Send Email For New Password ==================//
			$template  =  EmailTemplate::Select('em_tm_id','title','subject','message')->where('em_tm_id',3)->first();
			$sitelogo='<img src="'.asset(config('')).'uploads/654/100/ff=ffffff/logo/logo-4.png">';

			$password=$tmppassword;

			$str=str_replace("{#sitelogo}",$sitelogo,$template->message);
			$str=str_replace("{#name}",$user->first_name,$str);
			$str=str_replace("{#email}",$user->email,$str);
			$str=str_replace("{#password}",$password,$str);
			
			$goesefromemail='noreply@baheti-ivf.com';
			
			Mail::send('email.email',compact('str'), function ($m) use ($user,$template,$goesefromemail) {
			$m->from('sandeepjangid.er@gmail.com');
			$m->to($user->email);
			$m->subject($template->subject);
			});
			
			
		

		} else {

		$status ="error";
		$message="Invalid Email";


		}
      //return json_encode(array('status'=>$status,'message'=>$message));

    }
    public function getForgotPasswordConfirm($userId,$passwordResetCode = null)
    {
        // Find the user using the password reset code
        if(!$user = Sentinel::findById($userId))
        {
            // Redirect to the forgot password page
            return Redirect::route('forgot-password')->with('error', Lang::get('auth/message.account_not_found'));
        }

        if($reminder = Reminder::exists($user))
        {
            if($passwordResetCode == $reminder->code)
            {
                return View('admin.auth.forgot-password-confirm');
            }
            else{
                return 'code does not match';
            }
        }
        else
        {
            return 'does not exists';
        }

        // Show the page
       // return View('admin.auth.forgot-password-confirm');
    }

    /**
     * Forgot Password Confirmation form processing page.
     *
     * @param Request $request
     * @param number $userId
     * @param  string   $passwordResetCode
     * @return Redirect
     */
    public function postForgotPasswordConfirm(Request $request, $userId, $passwordResetCode = null)
    {
        // Declare the rules for the form validation
        $rules = array(
            'password'         => 'required|between:3,32',
            'password_confirm' => 'required|same:password'
        );

        // Create a new validator instance from our dynamic rules
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            // Ooops.. something went wrong
            return Redirect::route('forgot-password-confirm', $passwordResetCode)->withInput()->withErrors($validator);
        }

        // Find the user using the password reset code
        $user = Sentinel::findById($userId);
        if (!$reminder = Reminder::complete($user, $passwordResetCode, $request->get('password')))
        {
            // Ooops.. something went wrong
            return Redirect::route('signin')->with('error', Lang::get('auth/message.forgot-password-confirm.error'));
        }

        // Password successfully reseted
        return Redirect::route('signin')->with('success', Lang::get('auth/message.forgot-password-confirm.success'));
    }

    /**
     * Logout page.
     *
     * @return Redirect
     */
   public function getLogout()
    {
		unset($_COOKIE['admin_login_cookie']);
        // Log the user out
        Sentinel::logout();
		session::forget('ADMIN');
		session::forget('redirectpage');
        // Redirect to the users page
        return Redirect::to('cpmin/signin')->with('success', 'You have successfully logged out!');
    }
}
