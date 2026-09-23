<?php
namespace App\Http\Controllers;

use App\Blog;
use App\BlogComment;
use App\BlogViewer;
use App\ContactUs;
use App\EmailTemplate;
use App\FAQ;
use App\Gallery;
use App\Helpers\Thumbnail;
use App\Mail\EnquiryEmail;
use App\Newsletter;
use App\User;
use App\Order;
use App\OrderItem;
use App\ProductImages;
use App\ProductPrice;
use App\Products;
use App\Promocode;
use App\SociallyConcious;
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
use Hash;
use Validator;

//use Razorpay\Api\Api;
use View;

class HomeController extends Controller {

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

	public function index() {
		return view('landing');
	}

	public function login(Request $req){
        if (Sentinel::check()) {
			Sentinel::logout();
			// Redirect to the users page
			return Redirect::to('/login')->with('success', 'You have successfully logged out!');
        }
        else{
                return view('auth.login');
        }
    }

	public function postlogin(Request $req)
	{

        try {         
			
            $data = User::where('email',$req->email)->first();
            if ($data) {
                if (Hash::check($req->password, $data->password)) {
                    $user = Sentinel::findById($data->id);
                    Sentinel::login($user);
                // }
				// if(Sentinel::inRole('salesofficier'))
                // {	
					$output['status']		= 'success';
					$output['success']		= true;
					$output['slideToTop']	= true;
					$output['success_msg']	= "Thank-You! You are successfully login.";
					$output['slideToTop']	= true;
					$output['url']			= route('salesofficer.dashboard');
					return response()->json($output);
					
				}
				return response()->json(['errorArray'=>'','error_msg'=>"Sorry You are not a SalesOfficer for login.",'msgColor'=>"Red",'slideToTop'=>'yes']);
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


	public function logout(Request $req){
		Sentinel::logout();
        // Redirect to the users page
        return Redirect::to('/')->with('success', 'You have successfully logged out!');
	}

	public function static_page($slug) {
		$content = StaticPage::select('*')->where('slug', $slug)->first();
		if ($content) {
			$Pagetitle = $content->page_title;
			$Pagedescription = $content->meta_description;
			$Pagekeyword = $content->html_title;

			return view('page.static_page', compact('content', 'Pagetitle', 'Pagedescription', 'Pagekeyword', 'slug'));
		} else {

			return abort(404);
		}
	}
 }
