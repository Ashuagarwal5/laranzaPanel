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
use App\Notification;
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
use App\PushNotification;
use App\User;
use Artisan;
use Cache;
use Cart;
use DB;
use Illuminate\Http\Request;
use Mail;
use Redirect;
use Sentinel;
use Session;
use URL;
use Validator;
use Artisaninweb\SoapWrapper\SoapWrapper;
use App\Soap\Request\GetConversionAmount;
use App\Soap\Response\GetConversionAmountResponse;
use SoapClient;
//use Razorpay\Api\Api;
use View;

class UkController extends Controller {

    protected $soapWrapper;
	public function __construct(SoapWrapper $soapWrapper)
	  {
		$this->soapWrapper = $soapWrapper;
	  }


    public function callCurl($url, $data, $action) {

		$soapUser = "primedb";  //  username
		 $soapPassword ="Prime@2020"; // password



		 $handle   = curl_init();
		 $config= 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36';

		 // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		  curl_setopt($curl, CURLOPT_USERAGENT, $config);
		 curl_setopt($handle, CURLOPT_URL, $url);
		 curl_setopt($handle, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml", 'SOAPAction: "' . $action . '"'));
		 curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		 curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword);
		 curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
		 curl_setopt($handle, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
		 $response = curl_exec($handle);
		 if (empty($response)) {
		   echo curl_error($handle),curl_errno($handle);
		 }
		 curl_close($handle);
		 return $response;
	   }
	public function index() {
       $list= Notification::where("status",'Pending')->orderBy('id', 'DESC')->first();
	 
		 if($list)
		 {
			if($list->user_id==0)
			{
				$data=User::join('role_users','role_users.user_id','=','users.id')
				->join('roles','roles.id','=','role_users.role_id')
				->where('DeviceToken','!=','0')
				->pluck('DeviceToken')->toArray();
			}
			else
			{
				$data=User::join('role_users','role_users.user_id','=','users.id')
				 ->join('roles','roles.id','=','role_users.role_id')
				 ->where('DeviceToken','!=','0')
				 ->where('users.id',$list->user_id)
				 ->pluck('DeviceToken')->toArray();
			}

				$arr=array();
				
			   echo "<pre>";
			  print_r($data);
			   PushNotification::send($list,$data);
				die();   
			 //  Notification::where('id',$list->id)->update(['status'=>'Sent']);
		 }
	     

   }


}
