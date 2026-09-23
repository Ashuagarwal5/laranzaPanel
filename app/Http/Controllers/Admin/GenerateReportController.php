<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Order;
use App\Tutorials;
use App\OrderShipment;
use App\OrderItem;
use App\OrderStatus;
use App\WebsiteSetting;
use App\EmailTemplate;
use App\User;
use App\Orderaddress;
use App\SentEmail;
use Illuminate\Http\Request as valRquest;
use Redirect;
use Sentinel;
use Session;
use View;
use DB;
use File;
use Datatables;
use Input;
use Response;
use Validator;
use App\Helpers\datehelper;
use Illuminate\Support\Facades\Storage;
use Mail;

class GenerateReportController extends CodespurController
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
	
	
	public function filter(Request $request)
	{
		$input = $request->all();     
		$month = $request->month; 
		$year  = $request->year;
		$data = Order::select('*'
		,DB::raw("DAYOFYEAR(created_at) < DAYOFYEAR(CURDATE()) , DAYOFYEAR(created_at)")
		)
		->get();
		//echo "<pre>"; print_r($data); die;
		return view('admin.generate-report.list');
}

	public function index(Request $request)
	{	
		$PARENT_ID=46;
		$order =Order::select('order_id'
		 ,DB::raw("(DATE_FORMAT(tbl_order.created_at,'%M')) as add_date"))
		 ->get();
		 $input = $request->all();
		 //echo "<pre>"; print_r($order); die;	
		return view('admin.generate-report.list',compact('PARENT_ID','order'));
	}
	//=============== All List Data Function ==========================//
	public function data(){		
			$order = Order::select('order_id'
				,(DB::raw("COUNT(tbl_order_item.order_id) as count"))
				,DB::raw("(DATE_FORMAT(tbl_order.created_at,'%d %M  %Y')) as add_date")
			)
			->where('status','pending')
			->orwhere('status','payment authentication failed')

				->join('order_item','order_item.order_id','order.order_id')->orderBy('order.order_id','desc')
				->join('users','users.id','order.member_id')->orderBy('order.order_id','desc')
				->groupBy('order_item.order_id')
				->get();				
				
	foreach($order as $key => $value)
   {
	   if($value->payment_method == 'Bank Transfer (Wire Transfer/Cheque/DD)')
	   {
		$result =explode('.',$value['bank_receipt']);
		$order[$key]->mime_type = $result[1];
		}
		else
		{
					$order[$key]->mime_type = '';

		}
   }    
		
		 return Datatables::of($order)
            ->addColumn('actions', '
				@if($status != "completed")
				<a title="Convert to completed" class="btn btn-success btn-xs purple" href="{{URL::to("admin/orders/payment-manage/$order_id")}}"data-toggle="modal" data-target="#modal-regular">
				<i class="fa fa-thumbs-up"></i>
				</a>
				<a title="Convert to failed" class="btn btn-warning btn-xs purple" href="{{URL::to("admin/orders/payment-failed/$order_id")}}"data-toggle="modal" data-target="#modal-regular">
				<i class="fa fa-thumbs-down"></i>
				</a>
			@endif
              ')
			->rawColumns(['actions'])
            ->make(true);
		
	}
	
	 public function getModalPayment($id = null)
    {
		$model = 'Payment Complete';
		$confirm_route = $error = null;
		$message = 'Are you sure to Complete Payment for this record';
		$confirm_route = route('payment-complete', ['id' => $id]);
		return View('admin/layouts/all_modal_confirmation', compact('error', 'model', 'confirm_route','message'));
    }
	 public function getModalPaymentFailed($id = null)
    {
		$model = 'Payment Cancel';
		$confirm_route = $error = null;
		$message = 'Are you sure to Cancel Payment for this record';
		$confirm_route = route('payment-fail', ['id' => $id]);
		return View('admin/layouts/all_modal_confirmation', compact('error', 'model', 'confirm_route','message'));
    }
	
	public function PaymentManage($id)
	{
		
		$state = 'completed';
		$comment = 'successfully payment';
	
	
		$order['state']=$state;
		$order['status']=$state;

		
		Order::updateOrCreate(['order_id' =>$id],$order);


			$orderstatus = new OrderStatus();
			$orderstatus->state=$state;
			$orderstatus->order_id=$id;
			$orderstatus->status=$state;
			$orderstatus->comment=$comment;
			$orderstatus->save();

			
			
			
			
				$Order = Order::select('shipment_status','discount_amount','delivery_option','shipping_amount','sub_total','order.created_at','increment_id','grand_total','order.order_id','user_profile.first_name','user_profile.last_name','user_profile.address',
		'user_profile.city','user_profile.state','member_id','order.status','country.cntry_name','mobile','order_address.first_name as ship_name','order_address.address as ship_address',
		'order_address.city as ship_city','region_id','c.cntry_name as ship_country','phone','payment_method'
				,DB::raw("(DATE_FORMAT(tbl_order.created_at,'%d %M  %Y')) as add_date")

			)
				->where('status','shipped')
			->orwhere('status','completed')
			->where('order.order_id',$id)

				->leftjoin('user_profile','user_profile.user_id','order.member_id')
				->leftjoin('country','country.cntry_id','user_profile.country')
				->leftjoin('order_address','order_address.ord_adrs_id','order.address_id')
				->leftjoin('country as c','c.cntry_id','order_address.country_id')

				->orderBy('order.order_id','desc')
				->first();
			if(count($Order)>0)
			{
			$Item  =	OrderItem::select('product_name','product_id','price')->where('order_id',$Order->order_id)->get();
			
			}
		
		 $orderId = $Order->increment_id;



				
				if($Order->delivery_option == 'download')
				{
					$Order->addresses = User::select('users.id','users.email','users.mobile as phone','first_name')
				   ->where('users.id',$Order->member_id)->first();
				}
				else
				{

			$Order->addresses = Orderaddress::
				join('country', 'country.cntry_id', '=', 'order_address.country_id')
				->select('order_address.first_name','order_address.last_name','order_address.pincode','order_address.email','order_address.phone','order_address.address','order_address.city','region')
				->where('order_address.order_id',$Order->order_id)
				->first();
			}
					$siteData=WebsiteSetting::select('goes_from_email','goes_from_name','site_name')->where('id',1)->first();
					$this->sender_email=$siteData->goes_from_email;
					$this->sender_site_name=$siteData->goes_from_name;

			Mail::send('emails.invoice', compact('Order','Item'), function ($m) use ($Order,$orderId) {
			$m->from($this->sender_email,@trans($this->sender_site_name) );
			$m->to($Order->addresses->email, $Order->addresses->first_name . ' ' . $Order->addresses->last_name);
			$m->subject($this->sender_site_name.' | Order Placed Successfully, New Order Id : # '.$orderId);
			});

		

			$html = View::make('emails.invoice',compact('Order','Item'))->render();
			$saveEmail 	   		  = new SentEmail();
			$saveEmail['title']   = "Invoice";
			$saveEmail['subject'] = "For Invoice : Order Id ".$orderId;
			$saveEmail['message'] = $html;
			$saveEmail->save();
			

	$notification = array(
			'message' =>  'Payment made successfully.', 
			'alert-type' => 'success'
			);
			return redirect('admin/orders/pending-order')->with($notification);
	}
	public function PaymentManageFail($id)
	{
		
		$state = 'failed';
		$comment = 'failed';
	
	
		$order['state']=$state;
		$order['status']=$state;

		
		Order::updateOrCreate(['order_id' =>$id],$order);


			$orderstatus = new OrderStatus();
			$orderstatus->state=$state;
			$orderstatus->order_id=$id;
			$orderstatus->status=$state;
			$orderstatus->comment=$comment;

			$orderstatus->save();
	$notification = array(
			'message' =>  'Payment canceled successfully.', 
			'alert-type' => 'success'
			);
			return redirect('admin/orders/pending-order')->with($notification);
	}
	//===================pending end===========================//
	//=============================completed start===========================//
	public function completedIndex()
	{	
		$PARENT_ID=46;
		return view('admin.orders.completedlist',compact('PARENT_ID'));
	}
	
	public function Completeddata(){
		
			$order = Order::select('paytm_transaction_id','delivery_option','shipment_status','payment_method','bank_receipt','order.created_at','increment_id','grand_total','order.order_id','first_name','last_name','member_id','order.status'
				,(DB::raw("COUNT(tbl_order_item.order_id) as count"))
				,DB::raw("(DATE_FORMAT(tbl_order.created_at,'%d %M  %Y')) as add_date")

			)
			->where('status','shipped')
			->orwhere('status','completed')

				->join('order_item','order_item.order_id','order.order_id')->orderBy('order.order_id','desc')
				->join('users','users.id','order.member_id')->orderBy('order.order_id','desc')
				->groupBy('order_item.order_id')
				->get();
				
				
				
		
		
		 return Datatables::of($order)
            ->addColumn('actions', '<a title="View Info" class="btn btn-success btn-xs purple" href="{{URL::to("admin/orders/show/$order_id")}}" data-toggle="modal" data-target="#modal-email">
				<i class="fa fa-eye"></i>
				
				</a>
				@if($shipment_status != "Shipped" && $delivery_option == "physical")
				<a title="Shipment Detail" class="btn btn-warning btn-xs purple" href="{{URL::to("admin/orders/shipment-detail/$order_id")}}" data-toggle="modal" data-target="#modal-regular">
				<i class="fa fa-truck"></i>
				</a>
				@endif
			
              ')
			->rawColumns(['actions'])
            ->make(true);
		
	}

	public function view($id){
		
		$order = Order::select('shipment_status','discount_amount','delivery_option','shipping_amount','sub_total','order.created_at','increment_id','grand_total','order.order_id','user_profile.first_name','user_profile.last_name','user_profile.address',
		'user_profile.city','user_profile.state','member_id','order.status','country.cntry_name','mobile','order_address.first_name as ship_name','order_address.address as ship_address',
		'order_address.city as ship_city','region_id','c.cntry_name as ship_country','phone','payment_method'
				,DB::raw("(DATE_FORMAT(tbl_order.created_at,'%d %M  %Y')) as add_date")

			)
				->where('status','shipped')
			->orwhere('status','completed')
			->where('order.order_id',$id)

				->leftjoin('user_profile','user_profile.user_id','order.member_id')
				->leftjoin('country','country.cntry_id','user_profile.country')
				->leftjoin('order_address','order_address.ord_adrs_id','order.address_id')
				->leftjoin('country as c','c.cntry_id','order_address.country_id')

				->orderBy('order.order_id','desc')
				->first();
				
		
			if(count($order)>0)
			{
			$items  =	OrderItem::select('product_name','product_id','price')->where('order_id',$order->order_id)->get();
			
			}
		
		
			
				
		return view('admin.orders.view',compact('order','items'));
	}
	public function InvoicePrint($id){
		
		$order = Order::select('shipment_status','discount_amount','delivery_option','shipping_amount','sub_total','order.created_at','increment_id','grand_total','order.order_id','user_profile.first_name','user_profile.last_name','user_profile.address',
		'user_profile.city','user_profile.state','member_id','order.status','country.cntry_name','mobile','order_address.first_name as ship_name','order_address.address as ship_address',
		'order_address.city as ship_city','region_id','c.cntry_name as ship_country','phone','payment_method'
				,DB::raw("(DATE_FORMAT(tbl_order.created_at,'%d %M  %Y')) as add_date")

			)
				->where('status','shipped')
			->orwhere('status','completed')
			->where('order.order_id',$id)

				->leftjoin('user_profile','user_profile.user_id','order.member_id')
				->leftjoin('country','country.cntry_id','user_profile.country')
				->leftjoin('order_address','order_address.ord_adrs_id','order.address_id')
				->leftjoin('country as c','c.cntry_id','order_address.country_id')

				->orderBy('order.order_id','desc')
				->first();
			if(count($order)>0)
			{
			$items  =	OrderItem::select('product_name','product_id','price')->where('order_id',$order->order_id)->get();
			
			}
		
		
			
		return View::make('admin.orders.print',compact('order','items'));

	}
	
	public function getModalShipment($id = null)
    {
		
		return View('admin/orders/shipment_detail', compact('id'));
    }
	
	public function shipmentDetailAdd(valRquest $request,$id)
	{
		
			$rules['tracking_url'] 			= "required";
			$rules['tracking_code'] 			= "required";
			$rules['shipment_date'] 			= "required";
			$rules['courier_company'] 			= "required";
			
				
			$errorMsg						= "Opps ! Please fill required fields.";
			
		
			
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes','status'=>'error','msgHead'=>'error','msg'=>$errorMsg]);
			}
			else
			{
	
			$input = $request->all();
			$input['order_id'] = $id;

			
			
		$message="Shipment Details Added Successfully";
		Order::updateOrCreate(['order_id' => $id], ['shipment_status'=>'Shipped']);

		$shipment = OrderShipment::updateOrCreate(['ord_shp_id' => null], $input);
			
				$user= 	Order::select('member_id','first_name','last_name','email')
									->leftjoin('user_profile','user_profile.user_id','order.member_id','email')
									->where('order_id',$id)->first();
				

			
			
				$template  =  EmailTemplate::Select('em_tm_id','title','subject','message')->where('em_tm_id',3)->first();
					$sitelogo='<img src="'.asset(config('constants.frontend.logo')).'">';
					
					$useremail=$user->email;
					
					$siteData=WebsiteSetting::select('goes_from_email','goes_from_name','site_name')->where('id',1)->first();
					$goesefromemail=$siteData->goes_from_email;
					$goesfromname=$siteData->goes_from_name;
					$site_name=$siteData->site_name;
					
					$first_name=$user->first_name;
					$last_name=$user->last_name;
					$site_url="www.careerhacks.in";
					
					$str=str_replace("{#sitelogo}",$sitelogo,$template->message);
					$str=str_replace("{#firstname}",$first_name,$str);
					$str=str_replace("{#lastname}",$last_name,$str);
					$str=str_replace("{#url}",$input['tracking_url'],$str);
					$str=str_replace("{#tracking_code}",$input['tracking_code'],$str);
					$str=str_replace("{#shipment_date}",$input['shipment_date'],$str);
					$str=str_replace("{#courier_company}",$input['courier_company'],$str);
					$str=str_replace("{#site_name}",$goesfromname,$str);
					$str=str_replace("{#site_url}",$site_url,$str);
					
					
					Mail::send('email.email',compact('str'), function ($m) use ($first_name,$template,$goesefromemail,$useremail,$goesfromname) {
					$m->from($goesefromemail, $goesfromname);
					$m->to($useremail, $first_name);
					$m->subject($template->subject);
					});
			
		return response()->json(['success'=>'true','status'=>'success','msgHead'=>'success','msg'=>'Shipment Details successfully added..','selfReload'=>'yes']);


		
	}
		 
		  
	
		

		
	}


	//================================== completed end ==============================//
	//================================== failed  start==============================//
	
		public function failedIndex()
	{	
		$PARENT_ID=46;
		return view('admin.orders.failed',compact('PARENT_ID'));
	}
	
	public function faileddata(){
		
			$order = Order::select('paytm_transaction_id','shipment_status','payment_method','bank_receipt','order.created_at','increment_id','grand_total','order.order_id','first_name','last_name','member_id','order.status'
				,(DB::raw("COUNT(tbl_order_item.order_id) as count"))
				,DB::raw("(DATE_FORMAT(tbl_order.created_at,'%d %M  %Y')) as add_date")

			)
			->where('status','failed')

				->join('order_item','order_item.order_id','order.order_id')->orderBy('order.order_id','desc')
				->join('users','users.id','order.member_id')->orderBy('order.order_id','desc')
				->groupBy('order_item.order_id')
				->get();
				
				
				
		
		
		 return Datatables::of($order)
            ->addColumn('actions', '<a title="View Info" class="btn btn-success btn-xs purple" href="{{URL::to("admin/orders/show/$order_id")}}" data-toggle="modal" data-target="#modal-email">
				<i class="fa fa-eye"></i>
				
				</a>
				<a title="Shipment Detail" class="btn btn-warning btn-xs purple" href="{{URL::to("admin/orders/shipment-detail/$order_id")}}" data-toggle="modal" data-target="#modal-regular">
				<i class="fa fa-truck"></i>
				
				</a>
			
              ')
			->rawColumns(['actions'])
            ->make(true);
		
	}


public function itemInfo($id = null)
{
		
		$order = Order::select('shipment_status','discount_amount','delivery_option','shipping_amount'
		,'sub_total','order.created_at','increment_id','grand_total','order.order_id','order.status'
				,DB::raw("(DATE_FORMAT(tbl_order.created_at,'%d %M  %Y')) as add_date")

			)
				->where('status','failed')
		//	->orwhere('status','pending')
			->where('order.order_id',$id)


				->first();
				
			if(count($order)>0)
			{
			$items  =	OrderItem::select('product_name','product_id','price')->where('order_id',$id)->get();
			
			}
		

			
				
		return view('admin.orders.iteminfo',compact('PARENT_ID','order','items'));
	}
public function itemInfoPending($id = null)
{
		
		$order = Order::select('shipment_status','discount_amount','delivery_option','shipping_amount'
		,'sub_total','order.created_at','increment_id','grand_total','order.order_id','order.status'
				,DB::raw("(DATE_FORMAT(tbl_order.created_at,'%d %M  %Y')) as add_date")

			)
			->orwhere('status','pending')
			->where('order.order_id',$id)


				->first();
				
			if(count($order)>0)
			{
			$items  =	OrderItem::select('product_name','product_id','price')->where('order_id',$id)->get();
			
			}
		

			
				
		return view('admin.orders.iteminfo',compact('PARENT_ID','order','items'));
	}
public function shipmentDetailView($id = null)
{
		
		$detail = OrderShipment::where('order_id',$id)
				->first();
		
		return view('admin.orders.shipmentview',compact('detail'));
	}
	
	
	
	
	
	
 }
