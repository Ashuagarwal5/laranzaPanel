<?php
namespace App\Http\Controllers;

use App\Country;
use App\EmailTemplate;
use App\Helpers\Product as product_helper;
use App\Helpers\Thumbnail;
use App\Logs;
use App\MyAddress;
use App\Order;
use App\Orderaddress;
use App\OrderItem;
use App\OrderStatus;
use App\ProductImages;
use App\Products;
use App\Promocode;
use App\State;
use App\User;
use App\WebsiteSetting;
use Cart;
use DB;
use Illuminate\Http\Request as valRquest;
use Mail;
use Redirect;
use Response;
use Sentinel;
use Session;
use URL;
use Validator;
use View;
use Softon\Indipay\Facades\Indipay;


class CheckoutController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	function __construct() {

		$this->middleware(function ($request, $next) {
			if (Sentinel::check()) {

				$this->userId = Sentinel::getUser()->id;
			}
			return $next($request);
		});
	}

	public function parseStringToInt($price) {

		$price = str_replace(',', '', $price);
		return $price;
	}

	public function index() {

		$country = DB::table('country')->orderBy('cntry_name', 'asc')->get();

		$cart_products = Cart::content();

		$cart_products->no_of_product = Cart::count();
		$cart_products->total = Cart::total();
		if (count((array) $cart_products) > 0) {

			if (Sentinel::check()) {

				return Redirect::route('checkout.user.address');

			}

		} else {
			return Redirect::route('page.view-cart');
		}

		return view('checkout.checkout_index', compact('cart_products', 'country'));

	}

	public function signin(valRquest $request) {

		$cart_products = Cart::content();
		$cart_products->no_of_product = Cart::count();
		$cart_products->total = Cart::total();

		if ($request->isMethod('post')) {
			$rules['mobileno'] = "required";
			$rules['password'] = "required|string";
			$errorMsg = "Oops ! Please fill required fields.";
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
			}

			$result = User::where('mobileno', $request->mobileno)
				->orWhere('email', $request->mobileno)
				->where('deleted_at', null)->first();
			//echo $result; die;
			try {

				//login user based on email or mobile number
				$mobile = $request->get('mobileno');
				$password = $request->get('password');

				$with_mob = array('mobileno' => $mobile, 'password' => $password);
				$with_email = array('email' => $mobile, 'password' => $password);
				if ($result != null) {
					if (Sentinel::authenticate($with_mob, false, true) || Sentinel::authenticate($with_email, false, true)) {
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

							$output['url'] = route('checkout.user.address');

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

		return view('checkout.sign_in', compact('cart_products'));

	}

	public function biling(valRquest $request) {

		$country = DB::table('country')->orderBy('cntry_name', 'asc')->get();

		$cart_products = Cart::content();
		$cart_products->no_of_product = Cart::count();
		$cart_products->total = Cart::total();

		if ($request->isMethod('post')) {

			$rules['first_name'] = "required | alpha";
			$rules['last_name'] = "required|alpha";
			$rules['country'] = "required";
			$rules['city'] = "required";
			$rules['state'] = "required";
			$rules['postalcode'] = "required|numeric";
			// $rules['phone']                   = 'required|regex:/^([0-9\s\-\+\(\)]*)$/';
			$rules['address'] = "required";

			$rules['email'] = "required|email|unique:users,email";
			$rules['mobileno'] = "required|numeric|unique:users,mobileno";
			///  $rules['password']                           = "required|between:3,32";
			//  $rules['password_confirmation']              = "required|same:password";

			$customMessages = [
				'mobileno.unique' => 'It seems that you are already registered with us,
    			Please use login instead of Guest checkout.',
				'email.unique' => 'It seems that you are already registered with us,
    			Please use login instead of Guest checkout.',
				// 'phone.regex' => 'phone will only contains -, +, (',
				'mobileno.required' => 'Mobile number required',
				'mobileno.numeric' => 'Enter valid mobile number ',
			];

			//echo  "<pre>"; print_r($customMessages);

			$errorMsg = "Oops ! Please fill required fields.";
			$validator = Validator::make($request->all(), $rules, $customMessages);
			if ($validator->fails()) {
				return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
			}

			$password = mt_rand(100000, 999999);
			$register = array(
				'first_name' => $request->get('first_name'),
				'last_name' => $request->get('last_name'),
				'email' => $request->get('email'),
				'mobileno' => $request->get('mobileno'),
				'password' => $password,
			);

			$activate = true;

			$user = Sentinel::registerAndActivate($register);
			//$user = Sentinel::register( $register, $activate);

			$role = Sentinel::findRoleByName('User');
			$role->users()->attach($user);

			//send siple mail on user's email
			// $site_email = WebsiteSetting::select('goes_from_email')->first();
			// $from = $site_email->goes_from_email;
			// $to = $user->email;
			// $subject = "Hempstrol : Account Created Succesfully !";
			// $message = "Hi ".$user->first_name." ".$user->last_name." \r\n".
			// 			"Your account has been created successsfully."." \r\n".
			// 			"You can login with your email and password."." \r\n".
			// 			"E-mail :".$user->email." \r\n".
			// 			"Password :".$password." \r\n".
			// "\r\n Regards  Team Hempstrol !.";
			// $headers = "From:" . $from;

			// mail($to,$subject,$message, $headers);

			//=============================email===============================================
			$data = DB::table('website_settings')->select('logo', 'goes_from_email', 'contact_email', 'admin_email', 'goes_from_name', 'site_name')->where('id', 1)->first();

			$path = URL::to(Thumbnail::image("logo/$data->logo", "200", "65", "ffffff"));
			$sitelogo = '<img src="' . $path . '" alt="logo" width="108">';

			$template_data = EmailTemplate::where('em_tm_id', 1)->first();
			$full_name = $user->first_name . ' ' . $user->last_name;

			$goes_from_email = $data->goes_from_email;
			$adminemail = $data->contact_email;
			$siteName = $data->goes_from_name;
			$to_mail = $user->email;

			$str = str_replace("{#sitelogo}", $sitelogo, $template_data->message);
			$str = str_replace("{#name}", $full_name, $str);

			$str = str_replace("{#email}", $user->email, $str);
			$str = str_replace("{#password}", $password, $str);
			$str = str_replace("{#site_name}", $data->site_name, $str);

			$send_mail = Mail::send('email.email', ['str' => $str], function ($m) use ($sitelogo, $goes_from_email, $siteName, $to_mail, $template_data, $full_name) {
				$m->from($goes_from_email, $siteName);
				$m->to($to_mail, $full_name);
				$m->subject($template_data->subject);
			});
			//=============================end email===========================================

			$userdata = Sentinel::findById($user->id);

			Sentinel::login($userdata, false);

			$address = $request->address . " " . isset($request->address) ? $request->address : 'Not Found';
			$user_address = new MyAddress;
			$user_address->user_id = Sentinel::getUser()->id;
			$user_address->first_name = $request->first_name;
			$user_address->last_name = $request->last_name;
			$user_address->city = $request->city;
			$user_address->country = $request->country;
			$user_address->state = $request->state;
			$user_address->address = $address;
			$user_address->postalcode = $request->postalcode;
			$user_address->phone = $request->phone;

			if ($request->company_name) {
				$user_address->company_name = $request->company_name;
			}

			if ($request->apartment) {
				$user_address->apartment = $request->apartment;
			}

			$user_address->save();

			$output['msg'] = "Thank-You!.";
			$output['msgHead'] = "Success ! ";
			$output['msgType'] = "success";
			$output['status'] = 'success';
			$output['success'] = true;
			$output['slideToTop'] = true;
			$output['success_msg'] = "Thank-You! ";
			$output['slideToTop'] = true;

			$address_id = $user_address->address_id;

			$this->order_store($user_address);

			$output['url'] = route('checkout.payment.page');
			return response()->json($output);

		}

		return view('checkout.biling-address', compact('cart_products', 'country'));

	}

	public function user_address(valRquest $request) {

		$country = DB::table('country')->orderBy('cntry_name')->get();

		if (!Sentinel::check()) {
			return Redirect::route('checkout-cart');
		}

		$cart_products = Cart::content();
		$cart_products->no_of_product = Cart::count();
		$cart_products->total = Cart::total();

		$my_address = MyAddress::select('my_address.*', 'state as state_name', 'country.cntry_name as country_name')
			->where('user_id', Sentinel::getUser()->id)
			->leftJoin('country', 'country.cntry_id', 'my_address.country')
			->orderBy('set_as_default', 'DESC')
			->get();

		$user_details = Sentinel::getUser();

		if ($request->isMethod('post')) {

			if ($request->shipping_address) {
				$rules['shipping_address'] = "required|numeric";

			} else {

				$rules['first_name'] = "required | alpha";
				$rules['last_name'] = "required|alpha";
				$rules['country'] = "required";
				$rules['city'] = "required";
				$rules['state'] = "required";
				$rules['postalcode'] = "required|numeric";
				$rules['phone'] = 'required|regex:/^([0-9\s\-\+\(\)]*)$/';
				$rules['address'] = "required";
			}

			$custom_message = [
				'phone.regex' => 'phone will only contains -, +, (',
			];
			$errorMsg = "Oops ! Please fill required fields.";
			$validator = Validator::make($request->all(), $rules, $custom_message);
			if ($validator->fails()) {
				return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
			}

			if ($request->shipping_address) {

				$address_id = $request->shipping_address;

				$user_address = MyAddress::where('address_id', $address_id)->first();
			} else {
				$address = $request->address . " " . isset($request->apartment) ? $request->apartment : 'Not Found';

				$user_address = new MyAddress;
				$user_address->user_id = Sentinel::getUser()->id;
				$user_address->first_name = $request->first_name;
				$user_address->last_name = $request->last_name;
				$user_address->city = $request->city;
				$user_address->country = $request->country;
				$user_address->state = $request->state;
				$user_address->address = $address;
				$user_address->postalcode = $request->postalcode;
				$user_address->phone = $request->phone;

				if ($request->company_name) {
					$user_address->company_name = $request->company_name;
				}

				if ($request->apartment) {
					$user_address->apartment = $request->apartment;
				}

				$user_address->save();

				$address_id = $user_address->address_id;
			}

			$this->order_store($user_address);

			$output['msg'] = "Thank-You!.";
			$output['msgHead'] = "Success ! ";
			$output['msgType'] = "success";
			$output['status'] = 'success';
			$output['success'] = true;
			$output['slideToTop'] = true;
			$output['success_msg'] = "Thank-You! ";
			$output['slideToTop'] = true;

			$output['url'] = route('checkout.payment.page');
			return response()->json($output);

		}

		return view('checkout.user_address', compact('cart_products', 'user_details', 'my_address', 'country'));

	}

	public function payment_page(valRquest $request) {

		$order_id = session::get('order_id');

		$cart_products = Cart::content();
		$cart_products->no_of_product = Cart::count();
		$cart_products->total = Cart::total();

		if ($order_id && Sentinel::check()) {

			$user_details = Sentinel::getUser();
			$website_data = WebsiteSetting::where('id', 1)->first();

			$order_row = Order::where('order_id', $order_id)->first();

			$order_address = Orderaddress::select('order_address.*', 'order_address.region as state_name', 'country.cntry_name as country_name')
				->where('order_id', $order_id)
				->leftJoin('country', 'country.cntry_id', 'order_address.country_id')
				->first();

			if ($order_row->grand_total > 0) {

			} else {

				$order_row->grand_total = 1;
			}
			
			
				$data = [
			//'tid' => Session::get('order_id').time(), # Transaction ID.
			'tid' => Session::get('order_id'), # Transaction ID.
			'txnid' => Session::get('order_id'), # Transaction ID.
			//'txnid' => Session::get('order_id').time(), # Transaction ID.
			'amount' => $order_row->grand_total, # Amount to be charged.
			'productinfo' => "Product Information",
			'firstname' => $order_address->first_name, # Payee Name.
			'email' => $order_address->email, # Payee Email Address.
			'phone' => $order_address->phone, # Payee Phone Number.
			'order_id' => Session::get('order_id'),
			'service_provider' => 'payu_paisa',
			];

		
			$order = Indipay::gateway('PayUMoney')->prepare($data);
			
			
			//echo  "<pre>"; print_r($order); die('order');
			
			
			return Indipay::process($order); 

			// $api_key = 'rzp_test_AJBjDsfLCOiBO3';
			// $api_secret = 'eDTHP7mHnEHmrZClGtF5Jial';

			$api_key = 'rzp_live_mIf0rv2rqpFZ5W';
			$api_secret = '9MicpZvB5Nz58gKNkZ8VIfdD';

			/*$order = $api->order->create(array(
				  'receipt' => $order_id,
				  'amount' => (int) ($order_row->grand_total*100),
				  'payment_capture' => 1,
				  'currency' => session()->get('currency') ? session()->get('currency') : 'INR',
				  //'currency' => 'INR'
				  )
				);

				Order::where('order_id',$order_id)->update([
				   'razorpay_order_id' =>  $order->id

			*/
			$order = Order::where('order_id', $order_id)->first();
			$grand_total = (int) ($order_row->grand_total * 100);

			return view('checkout.payment_page', compact('order_address', 'cart_products', 'order', 'order_row', 'user_details', 'grand_total', 'api_key', 'website_data'));
		} else {

			return Redirect::route('checkout-cart');
		}

	}



 public function responsePayU(valRquest $request)
    {

		 //$response = $_POST;   // for test mode
		
		  $response = Indipay::response($request);   // for live/secre mode
		
		
		//~ $body =  json_encode($response);
		//~ mail('navratanp@unv7.com','aristhealthcare Payment Response',$body);
		

		 
		 if(isset($response['status']) && isset($response['txnid'])){
			 
			 
			 $order_id   =  $response['txnid'];
			 
			 $order = Order::where('order_id', $order_id)->first();
			 
			 
			 if($order && $order->payment_status == 'Pending'){
				 
				 
			     if($response['status']  == 'success')
			     {
				 
						$state = 'payment completed';
						$comment = 'successfully payment';

						$orderup['payment_status'] = 'Paid';
						$orderup['gateway_payment_id'] = $response['mihpayid'];
						$orderup['payment_response'] = json_encode($response);

						$order_data = Order::updateOrCreate(['order_id' => $order_id], $orderup);

						$orderstatus = new OrderStatus();
						$orderstatus->state = $state;
						$orderstatus->order_id = $order_id;
						$orderstatus->status = $state;
						$orderstatus->comment = $comment;

						$orderstatus->save();
						
						
						if (!empty(session::get('cart_session'))) {

						if (session::get('cart_session.promo_code_id')) {
						$promoDetail = Promocode::select('uses_per_coupon', 'total_used')->where('promo_code_id', session::get('cart_session.promo_code_id'))->first();

						if (count((array) $promoDetail) > 0) {
						  Promocode::where('promo_code_id', session::get('cart_session.promo_code_id'))->update(array('total_used' => $promoDetail->total_used + 1));
						}

						}

						}
						Session::forget('order_id');
						Session::forget('cart_session');

						Cart::destroy();
						
						Session::flash('payment_status_success', 'success'); 
						Session::flash('payment_message', 'yes'); 
			
			

				      // echo  "<pre>"; print_r($response); die('sandy success');
						
						return Redirect::route('checkout.info');
				 
			      }
			      else
			      {
					  
					  
					    $state = 'payment '.$response['status'];
						$comment = (isset($response['unmappedstatus']) ?  $response['unmappedstatus']: 'Payment Failed');

						$orderup['payment_status'] = 'Failed';
						$orderup['gateway_payment_id'] = $response['mihpayid'];
						$orderup['payment_response'] = json_encode($response);

						$order_data = Order::updateOrCreate(['order_id' => $order_id], $orderup);

						$orderstatus = new OrderStatus();
						$orderstatus->state = $state;
						$orderstatus->order_id = $order_id;
						$orderstatus->status = $state;
						$orderstatus->comment = $comment;

						$orderstatus->save();
						
						
						if (!empty(session::get('cart_session'))) {

						if (session::get('cart_session.promo_code_id')) {
						$promoDetail = Promocode::select('uses_per_coupon', 'total_used')->where('promo_code_id', session::get('cart_session.promo_code_id'))->first();

						if (count((array) $promoDetail) > 0) {
						  Promocode::where('promo_code_id', session::get('cart_session.promo_code_id'))->update(array('total_used' => $promoDetail->total_used + 1));
						}

						}

						}
						Session::forget('order_id');
						Session::forget('cart_session');

						Cart::destroy();
						
						
						Session::flash('payment_status_failed', 'failed'); 
						Session::flash('payment_message', 'yes'); 
					  
					    return Redirect::route('checkout.info');
				       // echo  "<pre>"; print_r($response); die('sandy not success');
				  
				  
				  }
			 
			 
			 }
			 
			 
		 }else{
			 
			 
			            Session::flash('payment_status_opps', 'not proper response'); 
						Session::flash('payment_message', 'yes'); 
					  
					    return Redirect::route('checkout.info');
					    
					    
			 
			            // echo  "<pre>"; print_r($response); die(' sandy error response');
			 
		}

		// echo  "<pre>"; print_r($response); die(' sandy last');
		


    }



	public function order_store($user_address) {

		$totalPrice = $this->parseStringToInt(Cart::total());
		$subtotalPrice = $this->parseStringToInt(Cart::subtotal());

		$country_currency = session()->get('currency') ? session()->get('currency') : 'INR';
		$currency_symbol = session()->get('currency_symbol') ? session()->get('currency_symbol') : '₹';

		if (!empty(session::get('cart_session.discount_value'))) {
			$total_Price = $this->returnCartTotalCheckout($totalPrice);
			$coupon_discount = $this->returnCartDiscountCheckout($totalPrice);
			if (!empty(session::get('cart_session.coupon_code'))) {
				$coupon_code = session::get('cart_session.coupon_code');
			} else {
				$coupon_code = "";
			}

		} else {
			$total_Price = $this->parseStringToInt(Cart::total());
			$coupon_discount = 0;
			$coupon_code = "";
		}

		$data['member_id'] = Sentinel::getUser()->id;

		$data['discount_amount'] = $coupon_discount;
		$data['discount_coupon_code'] = $coupon_code;
		$data['sub_total'] = $subtotalPrice;
		$data['order_amount'] = $totalPrice;
		$data['grand_total'] = $total_Price;
		$data['shipping_amount'] = 0;
		$data['tax_amount'] = 0;

		$data['currency_symbol'] = $currency_symbol;
		$data['order_currency_code'] = $country_currency;

		$data['shipping_method'] = "";
		$data['payment_method'] = 'PayUMoney';
		$data['total_item'] = Cart::count();
		$data['shipment_status'] = 'Pending';
		$data['shipping_description'] = "";

		$count = Order::where('order_id', Session::get('order_id'))->count();
		$Order = Order::updateOrCreate(['order_id' => null], $data);
		session::put('order_id', $Order->order_id);

		if ($count > 0) {
			Cart::restore(Session::get('order_id'));
		}

		Cart::store(Session::get('order_id'));

		$orderadd['order_id'] = $Order->order_id;
		$orderadd['customer_address_id'] = $user_address->address_id;
		$orderadd['region'] = $user_address->state;
		$orderadd['customer_id'] = $user_address->user_id;
		$orderadd['pincode'] = $user_address->postalcode;
		$orderadd['first_name'] = $user_address->first_name;
		$orderadd['last_name'] = $user_address->last_name;

		$orderadd['email'] = Sentinel::getUser()->email;
		$orderadd['phone'] = $user_address->phone;
		$orderadd['address'] = $user_address->address;
		$orderadd['city'] = $user_address->city;
		$orderadd['country_id'] = $user_address->country;
		$orderadd['apartment'] = $user_address->apartment;
		$orderadd['company_name'] = $user_address->company_name;

		$Orderaddress = Orderaddress::updateOrCreate(['order_id' => $Order->order_id], $orderadd);

		$orderaddup['address_id'] = $Orderaddress->ord_adrs_id;
		Order::updateOrCreate(['order_id' => $Order->order_id], $orderaddup);

		$OrderItem = OrderItem::where('order_id', Session::get('order_id'));
		if (count((array) $OrderItem) > 0) {
			$OrderItem->forceDelete();
		}

		foreach (Cart::content() as $key => $cart) {

			$product = Products::where('id', $cart->id)->first();

			$OrderItem = new OrderItem();
			$OrderItem->order_id = $Order->order_id;
			$OrderItem->product_id = $cart->id;
			$OrderItem->shipping_cost = 0;
			$OrderItem->cart_id = $cart->rowId;
			$OrderItem->member_id = Sentinel::getUser()->id;

			$OrderItem->product_option = '';

			$OrderItem->product_name = $cart->name;
			$OrderItem->product_description = $product->product_description;
			$OrderItem->product_image = ProductImages::get_image($product->id);
			$OrderItem->product_type = 'simple';
			$OrderItem->product_color = isset($cart->options['color']) ? $cart->options['color'] : '';
			$OrderItem->product_size = isset($cart->options['size']) ? $cart->options['size'] : '';

			$OrderItem->quantity_invoice = 0;
			$OrderItem->quantity_order = $cart->qty;
			$OrderItem->quantity_refund = 0;
			$OrderItem->quantity_shipped = 0;

			$OrderItem->price = $cart->price;
			$OrderItem->base_price = $cart->price;
			$OrderItem->orignal_price = $cart->price;

			$OrderItem->tax_percentage = 0;
			$OrderItem->row_total = ($cart->price * $cart->qty);

			$OrderItem->rowtotal_includetax = ($cart->price * $cart->qty);

			$OrderItem->commission_amount = 0;
			$OrderItem->order_currency_code = $country_currency;
			$OrderItem->currency_symbol = $currency_symbol;

			$OrderItem->save();
		}

		$orderstatus['order_id'] = $Order->order_id;
		$orderstatus['state'] = $Order->state;
		$orderstatus['status'] = $Order->status;
		$orderstatus['comment'] = "";

		$OrderStatus = OrderStatus::updateOrCreate(['order_id' => Session::get('order_id')], $orderstatus);

		return true;

	}

	public function payment_return(valRquest $request) {

		$inputs = $request->all();
		if (isset($inputs['hidden'])) {
			$order_id = $inputs['hidden'];

			$order_row = Order::where('order_id', $order_id)->where('razorpay_order_id', $inputs['razorpay_order_id'])->first();

			if ($order_row) {
				$state = 'payment completed';
				$comment = 'successfully payment';

				$orderup['payment_status'] = 'Paid';
				$orderup['razorpay_payment_id'] = $inputs['razorpay_payment_id'];
				$orderup['payment_response'] = json_encode($inputs);

				$order_data = Order::updateOrCreate(['order_id' => $order_id], $orderup);

				$orderstatus = new OrderStatus();
				$orderstatus->state = $state;
				$orderstatus->order_id = $order_id;
				$orderstatus->status = $state;
				$orderstatus->comment = $comment;

				$orderstatus->save();

				if (!empty(session::get('cart_session'))) {

					if (session::get('cart_session.promo_code_id')) {
						$promoDetail = Promocode::select('uses_per_coupon', 'total_used')->where('promo_code_id', session::get('cart_session.promo_code_id'))->first();

						if (count((array) $promoDetail) > 0) {
							Promocode::where('promo_code_id', session::get('cart_session.promo_code_id'))->update(array('total_used' => $promoDetail->total_used + 1));
						}

					}

				}
				Session::forget('order_id');
				Session::forget('cart_session');

				Cart::destroy();

				//send siple mail on user's email
				$this->send_mail_touser($order_id);
				return Redirect::route('checkout.info');
			} else {
				return redirect('/');
			}

		}

		//echo  "<pre>"; print_r($inputs);
		//mail("navratanp@unv7.com","My razorpay Response",'Hello');
	}

	public function cash_on_delivery(valRquest $request) {
		$order_id = $request->order_id;
		$order_row = Order::where('order_id', $order_id)->first();
		$state = 'product ordered';

		$order_address = Orderaddress::select('country.cntry_name as country_name')
			->join('country', 'country.cntry_id', 'order_address.country_id')
			->where('order_address.order_id', $request->order_id)
			->first();
		$amount = (int) Cart::total();

		//remove comma from cart price
		$a = Cart::total();
		$b = str_replace(',', '', $a);
		$amount = (int) $b;

		//if country is not  india then show error
		if ($amount < 50000) {
			if ($order_address->country_name == 'India') {
				$comment = ' product successfully ordered';
				$orderup['payment_status'] = 'Pending';
				$orderup['payment_method'] = 'cod';

				$order_data = Order::updateOrCreate(['order_id' => $order_id], $orderup);

				$orderstatus = new OrderStatus();
				$orderstatus->state = $state;
				$orderstatus->order_id = $order_id;
				$orderstatus->status = $state;
				$orderstatus->comment = $comment;

				$orderstatus->save();

				if (!empty(session::get('cart_session'))) {

					if (session::get('cart_session.promo_code_id')) {
						$promoDetail = Promocode::select('uses_per_coupon', 'total_used')->where('promo_code_id', session::get('cart_session.promo_code_id'))->first();

						if (count((array) $promoDetail) > 0) {
							Promocode::where('promo_code_id', session::get('cart_session.promo_code_id'))->update(array('total_used' => $promoDetail->total_used + 1));
						}

					}

				}
				Session::forget('order_id');
				Session::forget('cart_session');

				Cart::destroy();

				//send email on user's email
				$user = User::where('id', Sentinel::getUser()->id)->first();
				$this->send_mail_touser($request->order_id);

				$cod = 'cod';

				$output['msg'] = "Thank-You! Order Created Successfully.";
				$output['msgHead'] = "Success ! ";
				$output['msgType'] = "success";
				$output['status'] = 'success';
				$output['success'] = true;
				$output['slideToTop'] = true;
				$output['success_msg'] = "Thank-You! ";
				$output['url'] = route('checkout.cod-info');
				$output['slideToTop'] = true;
				return response()->json($output);
				//return Redirect::route('checkout.info');
			} else {
				$errorMsg = "Cash on Delivery feature is not available at your country!";
				return response()->json(['errorArray' => [], 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
			}

		} else {

			$errorMsg = "Sorry, We don’t accept COD order more than Rs. 50000/-";
			return response()->json(['errorArray' => [], 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
		}

	}

	public function checkCouponCode(valRquest $request) {
		//Session::forget('cart_session');

		$coupon_code = $request->get('coupon_code');
		if (!Sentinel::check()) {
			$valid = "Login First";
			return response()->json(['error_msg' => $valid, 'slideToTop' => 'yes', 'status' => 'error']);
		}

		$rules['coupon_code'] = "required";

		$errorMsg = "Oops ! Please fill required fields.";

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes', 'status' => 'error']);
		} else {
			$promocode_detail = Promocode::select('coupon_code', 'uses_per_customer', 'uses_per_coupon', 'promo_code.promo_code_id', 'discount', 'apply_as', 'apply_min_price', 'apply_max_price', 'from_date', 'to_date', 'total_used')
				->where('promo_code.coupon_code', $coupon_code)
				->where('promo_code.from_date', '<=', strtotime(date('Y/m/d')))
				->where('promo_code.to_date', '>=', strtotime(date('Y/m/d')))
				->first();

			if (!isset($this->userId) && count((array) $promocode_detail) == 0) {
				return json_encode(array('valid' => "Login First"));
			}

			if (count((array) $promocode_detail) > 0) {

				if ($promocode_detail->uses_per_coupon == $promocode_detail->total_used && ($promocode_detail->uses_per_coupon != 0) && ($promocode_detail->total_used != 0)) {
					return response()->json(['error_msg' => 'Sorry Coupon is finished', 'slideToTop' => 'yes', 'status' => 'error']);
				}

				if (isset($this->userId)) {
					$promocode_detail->userused = Order::select('discount_coupon_code')
						->where('discount_coupon_code', $coupon_code)
						->where('member_id', $this->userId)
						->where('created_at', '<=', date('Y-m-d', strtotime($promocode_detail->to_date)))
						->where('created_at', '>=', date('Y-m-d', strtotime($promocode_detail->from_date)))
						->count();
					if ($promocode_detail->userused >= $promocode_detail->uses_per_customer && $promocode_detail->uses_per_customer != 0) {
						return response()->json(['error_msg' => 'Sorry!Your Coupon Limit is Out', 'slideToTop' => 'yes', 'status' => 'error']);
					}

				}

				$grand_total = product_helper::parseStringToInt(Cart::total());

				if ($promocode_detail->apply_min_price <= $grand_total && $promocode_detail->apply_max_price >= $grand_total) {

				} else {

					$msg = 'This coupon code is valid for min purchase of Rs. ' . $promocode_detail->apply_min_price . ' OR maximum purchase of Rs. ' . $promocode_detail->apply_max_price;

					return response()->json(['error_msg' => $msg, 'slideToTop' => 'yes', 'status' => 'error']);

				}

				if (session::get('cart_session.coupon_code')) {
					return response()->json(['error_msg' => 'Sorry! Already applied a coupon', 'slideToTop' => 'yes', 'status' => 'error']);

				}

				//check discount amount should less then total amount
				if ($grand_total <= $promocode_detail->discount) {
					return response()->json(['error_msg' => 'Sorry! Amount is too low for this coupon. Try another .', 'slideToTop' => 'yes', 'status' => 'error']);
				}

				if (session::get('cart_session.coupon_code') != $coupon_code) {
					$cart_session = $this->fetchDiscountamount($promocode_detail, $coupon_code);
					$cartcontent = Cart::Content();

					$cart_session['cart_total'] = $this->returnCartTotal($promocode_detail);
					$cart_session['coupon_discount'] = $this->returnCartDiscount();
					$valid = "Yes";
					unset($cart_session['promo_code_id']);

					$output['loginstatus'] = 'success';
					$output['msg'] = "Apply successfully.";
					$output['msgHead'] = "Success ! ";
					$output['msgType'] = "success";
					$output['status'] = 'success';
					$output['success'] = true;
					$output['slideToTop'] = true;
					$output['success_msg'] = "Apply successfully";
					$output['slideToTop'] = true;
					$output['cart_session'] = $cart_session;
					$output['valid'] = $valid;
					$output['url'] = route('page.view-cart');

					return response()->json($output);

				} else {
					$valid = "This coupon code not valid for this cart";
					return response()->json(['error_msg' => $valid, 'slideToTop' => 'yes', 'status' => 'error']);

				}

			} else {
				$valid = "Plaese enter valid coupon code";
				return response()->json(['error_msg' => $valid, 'slideToTop' => 'yes', 'status' => 'error']);
			}

		}

	}

	public function returnCartTotalCheckout($total) {
		if (session::get('cart_session.discount_value') == "%") {
			$cart_total = $total - product_helper::discountonCart(session::get('cart_session.discount'), $total);
		} elseif (session::get('cart_session.discount_value') == "rs") {
			$cart_total = $total - session::get('cart_session.discount');
		} else {
			$cart_total = Cart::total();
		}

		return $cart_total;
	}

	public function returnCartDiscountCheckout($total) {

		if (session::get('cart_session.discount_value') == "%") {
			$cart_discount = product_helper::discountonCart(session::get('cart_session.discount'), $total);
		} else {
			$cart_discount = session::get('cart_session.discount');
		}

		return $cart_discount;
	}

	public function returnCartTotal($promocode_detail) {
		if ($promocode_detail->apply_as == "percentage of product price discount") {
			//	 $cart_total = $value->price*$promocode_detail->discount/100;

			$cart_total = product_helper::parseStringToInt(Cart::total()) - product_helper::discountonCart(session::get('cart_session.discount'), product_helper::parseStringToInt(Cart::total()));
		} elseif ($promocode_detail->apply_as == "fixed amount discount") {
			$cart_total = product_helper::parseStringToInt(Cart::total()) - session::get('cart_session.discount');
		} else {
			$cart_total = Cart::total();
		}
		return $cart_total;
	}

	public function returnCartDiscount() {
		if (session::get('cart_session.discount_value') == "%") {
			$cart_discount = product_helper::discountonCart(session::get('cart_session.discount'), product_helper::parseStringToInt(Cart::total()));
		} else {
			$cart_discount = session::get('cart_session.discount');
		}

		return $cart_discount;
	}

	public function fetchDiscountamount($promocode_detail, $coupon_code = null) {
		if ($coupon_code != null) {
			$cart_session['coupon_code'] = $coupon_code;
		}

		if ($promocode_detail->apply_as == 'percentage of product price discount') {
			$cart_session['discount_value'] = "%";
			$cart_session['discount'] = $promocode_detail->discount;

		} else {
			$cart_session['discount_value'] = "rs";
			$cart_session['discount'] = $promocode_detail->discount;
		}

		$cart_session['promo_code_id'] = $promocode_detail->promo_code_id;
		session::put('cart_session', $cart_session);
		return $cart_session;
	}

	public function checkout_info() {
		
			
		if(Session::has('payment_message')){
			return view('checkout.checkout_info_payment');
			
		}else{
			
		  return redirect('/');	
		}
		
		
	}

	public function checkout_cod_info() {
		return view('checkout.checkout_cod_info');
	}

	public function send_mail_touser($order_id) {
		$order = Order::select('shipment_status', 'discount_amount', 'currency_symbol', 'payment_method', 'shipping_amount', 'sub_total', 'order.created_at', 'grand_total', 'order.order_id', 'member_id', 'country.cntry_name as ship_country', 'order_address.phone', 'order_address.first_name', 'order_address.last_name', 'order_address.address as ship_address', 'order_address.city as ship_city', 'order_address.region as state', 'order_address.pincode', 'phone', 'order_address.email', 'payment_method', DB::raw("(DATE_FORMAT(tbl_order.created_at,'%d %M  %Y')) as add_date")
		)
			->where('order.order_id', $order_id)
			->leftjoin('order_address', 'order_address.ord_adrs_id', 'order.address_id')
			->leftjoin('country', 'country.cntry_id', 'order_address.country_id')
			->first();
		if (!empty($order)) {
			$items = OrderItem::select('product_name', 'product_id', 'price', 'quantity_order', 'product_image', 'currency_symbol')
				->where('order_id', $order->order_id)
				->get();
		}
		$product_info = null;
		foreach ($items as $key => $value) {
			$product_info .= '<tr><td style="width: 20%;"><img src="' . URL::to(Thumbnail::image("productimg/$value->product_image", "200", "65", "ffffff")) . '" /></td><td style="vertical-align: top; width: 26.66%;"><span style="color:#333;  font-size: 15px; line-height: 21px;">' . $value->product_name . '</span></td><td style="vertical-align: top; width: 26.66%;"><span style="color:#333;  font-size: 15px;">Qty: ' . $value->quantity_order . '</span></td><td style="vertical-align: top; width: 26.66%;"><span style="color:#333;  font-size: 15px;">' . $value->currency_symbol . " " . $value->price . '</span></td></tr>';
		}

		//=============================email===============================================
		$data = DB::table('website_settings')->select('logo', 'goes_from_email', 'contact_email', 'admin_email', 'goes_from_name', 'site_name')->where('id', 1)->first();

		$path = URL::to(Thumbnail::image("logo/$data->logo", "200", "65", "ffffff"));
		$sitelogo = '<img src="' . $path . '" alt="logo" width="108">';

		$template_data = EmailTemplate::where('em_tm_id', 5)->first();
		$full_name = $order->first_name . ' ' . $order->last_name;

		$goes_from_email = $data->goes_from_email;
		$adminemail = $data->contact_email;
		$siteName = $data->goes_from_name;
		$to_mail = $order->email;

		$sub_total = $order->currency_symbol . $order->sub_total;
		$discount = $order->currency_symbol . $order->discount_amount;
		$total = $order->currency_symbol . $order->grand_total;
		$payment_method = ($order->payment_method == 'cod') ? 'Cash On Delivery' : $order->payment_method;

		$str = str_replace("{#logo}", $sitelogo, $template_data->message);
		$str = str_replace("{#order_id}", $order_id, $str);
		$str = str_replace("{#product_info}", $product_info, $str);

		$str = str_replace("{#sub_total}", $sub_total, $str);
		$str = str_replace("{#discount}", $discount, $str);
		$str = str_replace("{#total}", $total, $str);
		$str = str_replace("{#payment_method}", $payment_method, $str);

		$send_mail = Mail::send('email.email', ['str' => $str], function ($m) use ($sitelogo, $goes_from_email, $siteName, $to_mail, $template_data, $full_name) {
			$m->from($goes_from_email, $siteName);
			$m->to($to_mail, $full_name);
			$m->subject($template_data->subject);
		});
		//=============================end email===========================================
	}

}
