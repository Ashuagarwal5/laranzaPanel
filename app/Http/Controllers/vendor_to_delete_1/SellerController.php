<?php

namespace App\Http\Controllers\vendor;
use Illuminate\Http\Request as valRquest;
use App\Http\Requests;
use Validator;
use App\Http\Controllers\Admin\CodespurController;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

use File;
use Hash;
use Illuminate\Support\Facades\Request;
use Lang;
use Mail;
use Redirect;
use Sentinel;
use URL;
use View;
use App\User;
use App\State;
use App\RoleUser;
use App\SellerDetails;
use App\BmgServices;
use App\UserService;
use App\Products;
use App\Order;
use App\OrderItem;
use App\Newsletter;
use App\OrderStatus;
use App\PushNotification;
use Datatables;


use Session;
use DB;
use Carbon\Carbon;
use Response;

use Illuminate\Support\Facades\Input;
use Storage;

use Cache;
use Artisan;


use App\Helpers\datehelper;
use App\Http\Requests\SellerCompanyRequest;
use App\Http\Requests\PasswordupdateRequest;
use App\Http\Requests\ProfileupdateRequest;
use App\Http\Requests\SellerStoreRequest;
use App\Http\Requests\SellerPersonalRequest;



class SellerController extends CodespurController
{

    function __construct()
     {
		 $this->date=datehelper::dateformat();
          parent::__construct();
          
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
    
    
     public function landingPage()
   {
	   
           return redirect('/');
		
		return View('vendor.landing_page');
	}






    public function login()
    {
         return view('vendor.login');
    }
   public function getNoti(valRquest $request)
   {

            if($request->view == 'yes'){
                                SellerNotifications::where('seller_id',$this->userId)
	                                                 ->update(['read_status'=>"Read"]);
		    }

		    $all_noti = 	SellerNotifications::where('seller_id',$this->userId)->orderby('sel_ntf_id','desc')->get();
            $output = '';
            if(count($all_noti) > 0 ){
             foreach($all_noti as $key => $value){
                 $date =    date('d-M-y',strtotime($value->created_at ));
				 $output .= "
						<li>
						<a href='javascript:;'>
						<strong>".substr($value->notification_detail,0,30) ."</strong><br />
						<small><em>".$date."</em></small>
						</a>
						</li>
						";
			}


              $output .=  "<li class='divider'></li>
					 <li class='footer'>
            <a href=". route('seller.all.noti-get').">View all</a>
                     </li>";


		  }
		  else{
                 $output .= '<li><a href="javascript:;" class="text-bold text-italic">No Notification Found</a></li>';

			  }

                 $count = SellerNotifications::where('seller_id',$this->userId)->where('read_status','Unread')->count();


			$data = array(
			'notification'   => $output,
			'unseen_notification' => $count
			);
          echo json_encode($data);


   }






 public function getAnnouncement(valRquest $request)
   {

            if($request->view == 'yes'){
                         SellerAnnouncements::where('seller_id',$this->userId)
	                                ->update(['read_status'=>"Read"]);
		    }

		   $all_anc = 	SellerAnnouncements::where('seller_id',$this->userId)->orderby('sel_anc_id','desc')->get();
           $output = '';
           if(count($all_anc) > 0 ){
           foreach($all_anc as $key => $value){
                $date =    date('d-M-y',strtotime($value->created_at ));
			$output .= "
					<li>
					<a href='javascript:;'>
					<strong>".substr($value->announcement_detail,0,30)."</strong><br />
					<small><em>".$date."</em></small>
					</a>
					</li>
					";
			}

             $output .=  "<li class='divider'></li>
					 <li class='footer'>
            <a href=". route('seller.all.anc-get').">View all</a>
                     </li>";
		  }
		  else{
                 $output .= '<li><a href="javascript:;" class="text-bold text-italic">No Announcement Found</a></li>';
			  }
          $count = SellerAnnouncements::where('seller_id',$this->userId)->where('read_status','Unread')->count();

			$data = array(
			'notification'   => $output,
			'unseen_notification' => $count
			);
          echo json_encode($data);

   }

    public function getAllNoti()
    {

          $notifications = SellerNotifications::where('seller_id',$this->userId)->orderby('sel_ntf_id','desc')->get();
           $type ='Notifications';
       	return view('vendor.notification-all', compact('notifications','type'));
	}

	 public function getAllAnnouncement()
    {

          $notifications = SellerAnnouncements::where('seller_id',$this->userId)->orderby('sel_anc_id','desc')->get();
          $type ='Announcement';
       	return view('vendor.notification-all', compact('notifications','type'));
	}


	public function registration()
	{
		
		
		
		
		//die('ok');
		
		if(isset($this->userId)) { 
		$userinfo = User::where('id',$this->userId)->first();
		$state = State::orderby('st_name','asc')->get();
	}else{
		
		return redirect(route('seller.landing'));
		
		}
		return view('vendor.register', compact('state','userinfo'));
	}

	public function postRegister(valRquest $request)
	{
		
		 $this->validate($request, [
                     'first_name' => 'required|min:3',
                    'last_name' => 'required|min:3',
                    'email' => 'required|email',
                    'mobileno'=>'required',
                   
                    
                  
                    'company_name' => 'required|min:3',
                    'category' => 'required',
                    'city' => 'required',
                    'state'=>'required',
                    'company_telephone' => 'required',
                    'postcode'=>'required',
                    'address'=>'required',
                    'address'=>'required',
                    'designation'=>'required',
                    'store_name'=>'required|max:15|regex:/^[a-zA-Z0-9\-]+$/',
                    'location_on_map'=> 'required'

          ]);
          
          
          
		
		$seller = new SellerDetails();

		$seller->company_name        =  $request->get('company_name');
		$seller->category            =  $request->get('category');
		$seller->postcode            =  $request->get('postcode');

		$seller->city                =  $request->get('city');
		$seller->state               =  $request->get('state');
		$seller->company_telephone   =  $request->get('company_telephone');
		$seller->address             =  $request->get('address');
		$seller->user_id             =  $this->userId;
		$seller->first_name          =  $request->get('first_name');
		$seller->last_name           =  $request->get('last_name');
		$seller->email               =  $request->get('email');
		$seller->mobileno            =  $request->get('mobileno');
		$seller->shop_name           =  $request->get('store_name');
        preg_match("/[^\.\/]+\.[^\.\/]+$/", basename(URL::to('/')), $matches);
        if(isset($matches[0]))
	    $seller->shop_url            = strtolower($seller->shop_name.'.'.$matches[0]);
		
			
		$seller->designation         =  $request->get('designation');
		
		$seller->location_on_map     =  $request->get('location_on_map');
		$seller->latitude            =  $request->get('latitude');
		$seller->longitude           =  $request->get('longitude');
		
		$seller->save();



		$bmgservice = BmgServices::select('srv_id')->where('slug','dolovery- seller')->first();
		$userservice = new UserService();
		$userservice->member_id  =  $this->userId;
		$userservice->service_id  = $bmgservice->srv_id;
		$userservice->save();

		return json_encode(array('status'=>"success",'redirect'=>true,'message'=>'Succesfully Submit','url'=>route('seller.term-condition')));
	}

  public function termCondition()
  {
	  
	  if(isset($this->userId)) { 
		  
		$this->userId = Sentinel::getUser()->id;
		$userser = UserService::checkService($this->userId);
		if($userser)
		{
			if($userser->agree_terms=='Yes')
			return Redirect::to(URL::previous());
		}
		else
		{
			return Redirect::route('seller.landing');
		}
	}else{
		
		return redirect(route('seller.landing'));
		
		}
	  return view('vendor.term-condition');
  }

  public function acceptTermCondition(valRquest $request)
  {
		$this->validate($request, [
		'agree_terms' => 'required'
		]);
		
		UserService::where('member_id',$this->userId)
		->update(['agree_terms'=>$request->get('agree_terms')]);
		
		
		$role = Sentinel::findRoleByName('Seller');
		
		$checkRole = RoleUser::where('user_id',$this->userId)->where('role_id',$role->id)->first();
		
		if(empty($checkRole)){
			
			//die('okkhh');
		$input =            new RoleUser();
			$input->user_id    = $this->userId;
			$input->role_id    = $role->id;

			$input->save();
			
		}
		
		
		//~ $input['user_id'] = $this->userId;
		//~ $input['role_id'] = $role->id;
		
		//~ $sellerprofile=RoleUser::updateOrCreate(['user_id' => $this->userId , 'role_id' => $role->id ],$input);
		
		
		//~ $user = Sentinel::getUser();
		//~ $role->users()->attach($user);
		//$role->users()->attach(Sentinel::getUser());
	return json_encode(array('status'=>"success",'redirect'=>true,'message'=>'Thank you for registration','url'=>route('seller.success')));
  }
  public function successProcess()
  {
	  
	  if(isset($this->userId)) { 
	   return view('vendor.successProcess');
   }else{
	   
	     return redirect(route('seller.landing'));
	   }
  }
  
  
  
  /******************** Orders List Starts ***********************/
  
  public function order_list($order_type = null)
  {
	
        $order_type = $order_type;
		return view('vendor.orderlist',compact('order_type'));						 
  }
  
  public function order_data($order_type = null)
  {

		$query = OrderStatus::select('order_status.status','order_status.order_id',
		'order.member_id',
		'order.increment_id',
		'order.payment_method',
		'users.full_name',
				DB::raw("(DATE_FORMAT(tbl_order_status.created_at,'%d %M  %Y')) as order_date"),
				
				DB::raw("(SELECT SUM(tbl_order_item.row_total) FROM tbl_order_item WHERE  (tbl_order_item.order_id = tbl_order_status.order_id AND tbl_order_item.seller_id = tbl_order_status.seller_id ) ) as seller_row_total"),
				DB::raw("(SELECT COUNT(tbl_order_item.ord_item_id) FROM tbl_order_item WHERE  (tbl_order_item.order_id = tbl_order_status.order_id AND tbl_order_item.seller_id = tbl_order_status.seller_id ) ) as my_item_count"),

				DB::raw("(DATE_FORMAT(`tbl_order_status`.created_at,'%d %M  %Y')) as add_date")
				);
				
			$query->join('order','order.order_id','order_status.order_id') 
			->leftJoin('users','users.id','order.member_id')
			->join('order_item','order_item.seller_id','order_status.seller_id')
			->where('order_item.seller_id',Sentinel::getUser()->id)
			->orderBy('order_status.ord_status_id','desc')
			->groupBy('order_status.ord_status_id');
				
				
				if($order_type == 'pending-orders')
				{
					$query->where('order_status.status','pending');
				}
				if($order_type == 'failed-orders')
				{
					$query->where('order_status.status','failed');
				}
				if($order_type == 'completed-orders')
				{
					$query->where('order_status.status','completed');
				}
				
				if($order_type == 'awaiting-shipmment-order')
				{
					$query->where('order_status.status','order shipped');
					$query->orWhere('order_status.status','awaiting shipment');
				}
				
				
				
				if(Input::get('start') && Input::get('end') )
				{
					$query->whereBetween('order_status.created_at',array(Input::get('start'), Input::get('end')));
				}
				
				

				$order =  $query->get();
				
				
			
		
		 return Datatables::of($order)
            ->add_column('actions', '
            
               @if($status == "pending")
				<a title="Confirm Order" class="btn btn-success btn-xs confirm_confirm" data-toggle="modal" data-target="#modal-regular" href="{{URL::to("sellerpanel/orders/complete/$order_id")}}" >
				<i class="fa fa-check"></i>
				</a>
				
				
				<a title="Fail/Decline Order" class="btn btn-danger btn-xs fail_confirm"  data-toggle="modal" data-target="#modal-regular" href="{{URL::to("sellerpanel/orders/cancel/$order_id")}}" >
				<i class="fa fa-remove"></i>
				</a>
				
				@endif
				
				
				@if($status == "awaiting shipment" || $status == "order shipped")
				<a title="Update Delivery Info" class="btn btn-success btn-xs purple" href="{{URL::to("sellerpanel/orders/update-shipment/$order_id")}}" data-toggle="modal" data-target="#modal-regular">
				<i class="fa fa-motorcycle"></i>
				
				</a>
				@endif
				
				
				
				
				
				
				
				
              ')
			->rawColumns(['actions'])
            ->make(true);
		
	}



   public function shipment_update_page($id = null)
    {
		$model = 'Update Delivery Info';
		 $error = null;
		 
		 
		 
		 $whereIn = array('awaiting shipment','order shipped');
		 
		 
		 
				$order = OrderStatus::select('order_status.status','order_status.order_id')
				->join('order','order.order_id','order_status.order_id') 
				->whereIn('order_status.status',$whereIn)
				->where('order_status.seller_id',Sentinel::getUser()->id)
				->where('order_status.order_id',$id)
				->first();
				
				

				if(!$order )   {

				echo $error = "Something Went Wrong";
				die;

				}          

				$message = "";
		
		return View('vendor.shipment_update', compact('error','order','id', 'model','message'));
    }
    
	 public function shipment_update_post($id = null,valRquest $request)
    {
		
			$input=$request->all();
			$rules['shipment_status'] 		= "required";
			
			
			$msg							= "Please fill required fields.";
			$msgHead						= "Error !";
			$msgType						= "error";
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return response()->json(['errorArray'=>$validator->errors(),'msg'=>$msg,'msgHead'=>$msgHead,'msgType'=>$msgType,'slideToTop'=>'yes']);
			}
			else{
				
				
				
				
				 $whereIn = array('awaiting shipment','order shipped');
				
				
				$orderDetails = OrderStatus::select('order_status.status','order_status.order_id','seller_details.shop_name')
				->join('order','order.order_id','order_status.order_id') 
				->join('seller_details','seller_details.user_id','order_status.seller_id')
				->whereIn('order_status.status',$whereIn)
				->where('order_status.seller_id',Sentinel::getUser()->id)
				->where('order_status.order_id',$id)
				->first();
				
				
				
				
				//~ $orderDetails  = Order::select('shipment_status','order.status')
				//~ ->whereIn('status',$whereIn)
				//~ ->where('order_id',$id)
				//~ ->first();
				
				
				if($orderDetails){
				
				
				
				$state   = $request->get('shipment_status');
	
				$order['state']=$state;
				$order['status']=$state;
				
				
				$push = array();
				
				
				if($state  == 'completed'){
				$order['payment_status']= 'Paid';
				$push['status']	      = 'Your Order from '.$orderDetails->shop_name.' has been delivered !';
				}
				
				
				
				if($state  == 'order shipped'){
					
					
				$push['status']	      = 'Your Order from '.$orderDetails->shop_name.' is its on the way !';
				}

				
				$orderStatus['state'] = $state;
				$orderStatus['status'] = $state;
				
				
				OrderStatus::updateOrCreate(['order_id' =>$id, 'seller_id' => Sentinel::getUser()->id ],$orderStatus);
				
				
				 $whereInItem = array('Item Ready to Ship','order shipped');
				 
				 
				 
				
		        OrderItem::where('order_id', $id)->whereIn('order_status', $whereInItem)->where('seller_id', Sentinel::getUser()->id )->update(['order_status' =>$state ]);
		
		

				$orderData = Order::select('member_id')->where('order_id',$id)->first();
				
				$push['order_id'] 		= 1;
				$push['receiver_id']	= $orderData->member_id;
				$push = (object)$push;  
				
				//echo  "<pre>"; print_r($push); die('ok');
				 PushNotification::send('order_push', $push);
				 
				 
				
				$output['msg']				= "Order Delivery Status updated successfully";
				$output['msgHead']			= "Success ! ";
				$output['msgType']			= "success";
				$output['status']			= 'success';
				$output['resetform']	    = true;
				$output['slideToTop']		= true;
				$output['url']		        = route('seller.orders', 'awaiting-shipmment-order');
				
				return json_encode($output);
			  }else{
				  
				  
			   }
				
				
			}
			
		//die('ok');
	}
	
	





	
  /******************** Orders List Ends ***********************/
  
  
   public function order_data123456($order_type = null)
  {
		
		$query = OrderItem::select('order_item.ord_item_id','order.shipment_status','order_item.seller_id','order_item.order_id','order.increment_id','order_item.member_id','order_item.quantity_order','order_item.base_price','order.status as order_status','order.status','order_item.row_total','order.payment_method','order.grand_total','users.full_name',
				DB::raw("(DATE_FORMAT(tbl_order_item.created_at,'%d %M  %Y')) as order_date")
				);
				
				if($order_type == 'pending-orders')
				{
					$query->where('order.status','pending');
				}
				if($order_type == 'failed-orders')
				{
					$query->where('order.status','failed');
				}
				if($order_type == 'completed-orders')
				{
					$query->where('order.status','completed');
				}
				
				$order = $query->leftJoin('users','users.id','order_item.member_id')
				->join('order','order.order_id','order_item.order_id')
				->where('order_item.seller_id',Sentinel::getUser()->id)
				->orderBy('order_item.ord_item_id','desc')
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
            ->add_column('actions', '
				@if($status == "pending")
				<a title="Convert to completed" class="btn btn-success btn-xs purple" href="{{URL::to("sellerpanel/orders/complete/$ord_item_id")}}"data-toggle="modal" data-target="#modal-regular">
				<i class="fa fa-thumbs-up"></i>
				</a>
				<a title="Convert to failed" class="btn btn-warning btn-xs purple" href="{{URL::to("sellerpanel/orders/cancel/$ord_item_id")}}"data-toggle="modal" data-target="#modal-regular">
				<i class="fa fa-thumbs-down"></i>
				</a>
				@endif
				
				@if($status == "completed" || $status == "shipped")
				<a title="View Info" class="btn btn-success btn-xs purple" href="{{URL::to("sellerpanel/orders/show/$ord_item_id")}}" data-toggle="modal" data-target="#modal-email">
				<i class="fa fa-eye"></i>
				
				</a>
				@if($shipment_status != "Shipped")
				<a title="Shipment Detail" class="btn btn-warning btn-xs purple" href="{{URL::to("sellerpanel/orders/shipment-detail/$ord_item_id")}}" data-toggle="modal" data-target="#modal-regular">
				<i class="fa fa-truck"></i>
				</a>
				@endif
				@endif
				
				@if($status == "failed")
				<a title="View Info" class="btn btn-success btn-xs purple" href="{{URL::to("sellerpanel/orders/show/$ord_item_id")}}" data-toggle="modal" data-target="#modal-email">
				<i class="fa fa-eye"></i>
				</a>
				@endif
				
              ')
			->rawColumns(['actions'])
            ->make(true);
		
	}
	
  
  
  
  
  
  
  
  
	public function sales_report()
	{
         $start =   Input::get('start');
         $end   =   Input::get('end');
         
         
         $order_type =   Input::get('order_type');
         
          if(!$order_type)
          $order_type =  'all';
         
         if($end && $start && $order_type) {
			 
			// echo  $order_type; die('order_type');
		
			  return view('vendor.report.sales_report',compact('end','start','order_type'));
		 }
    
        return view('vendor.report.sales_report',compact('order_type'));
    
	}

	public function inventory_report()
	{
      
         $start =   Input::get('start');
         $end =   Input::get('end');
         
         if($end && $start){
			 
			  $products = Products::select('*',DB::raw("(DATE_FORMAT(`tbl_products`.created_at,'%d %M  %Y')) as add_date"))
			  ->where('seller_id',$this->userId)
			  ->whereBetween('created_at', array($start, $end))
			  ->get();

			  return view('vendor.report.inventory_report',compact('end','start','products'));
		 }
		 
      return view('vendor.report.inventory_report');
      
	}
  
  
  
  
  public function commingsoon()
  {
	  
	    //~ Cache::flush();	 
	   //~ Artisan::call('config:cache');
	   
	   
	$fromdate= date("Y-m-d ",strtotime('monday this week'));
	$todate= date("Y-m-d");

	$noOfProduct = Products::where('seller_id',$this->userId)
	->count();

	$wnoOfProduct = Products::where('seller_id',$this->userId)
	->where(DB::raw("(date(created_at))"),'>=',$fromdate)
	->where(DB::raw("(date(created_at))"),'<=',$todate)
	->count();


	$noOfOrder = Order::
	 Join('order_item','order_item.order_id','=','order.order_id')
	->where('order_item.seller_id',$this->userId)
	->groupby('order.order_id')
	->count();

	$wnoOfOrder = Order::	Join('order_item','order_item.order_id','=','order.order_id')
	->where('order_item.seller_id',$this->userId)
	->where(DB::raw("(date(`tbl_order`.created_at))"),'>=',$fromdate)
	->where(DB::raw("(date(`tbl_order`.created_at))"),'<=',$todate)
	->groupby('order.order_id')
	->count();

	 $totalOrder = Order::
	  Join('order_item','order_item.order_id','=','order.order_id')
	 ->where('order_item.seller_id',$this->userId)
	 ->groupby('order.order_id')
	 ->sum('grand_total');

	$wtotalOrder = Order::
	Join('order_item','order_item.order_id','=','order.order_id')
	->where('order_item.seller_id',$this->userId)
	->where(DB::raw("(date(`tbl_order`.created_at))"),'>=',$fromdate)
	->where(DB::raw("(date(`tbl_order`.created_at))"),'<=',$todate)
	->groupby('order.order_id')
	->sum('grand_total');


	$orders = Order::select(['order.grand_total as grand_total','order.order_id',DB::raw('DATE_FORMAT(tbl_order.created_at, "%d  %M %y ") as created_at'),DB::raw('DATE_FORMAT(tbl_order.created_at,"%Y,%m,%d") as x')])
	->Join('order_item','order_item.order_id','=','order.order_id')
	 ->Join('invoice','invoice.order_id','=','order.order_id')
	 ->where('order_item.seller_id',$this->userId)
	->where(DB::raw("(month(`tbl_order`.created_at))"),'=',date('m'))
	->where(DB::raw("(year(`tbl_order`.created_at))"),'=',date('Y'))
	->groupby('order.order_id')
	->orderBy('order.order_id', 'asc');


	$ordersd = DB::table(DB::raw("({$orders->toSql()}) as pro"))
	->mergeBindings($orders->getQuery())
	->select(DB::raw('sum(grand_total) AS y'),'x')
	->groupby('created_at')
	->get();


	if(isset($ordersd))
	$sale_value = json_encode($ordersd);

	//echo $sale_value;die;

	$sellerdetails	 = SellerDetails::join('state','state.state_id','=','seller_details.state')
	-> join('category','category.id','=','seller_details.category')
	->select('designation','first_name','last_name','mobileno','email','seller_details.bank_account_name','seller_details.account_no','seller_details.ifsc_code','seller_details.branch','seller_details.user_id','seller_details.status','seller_details.company_name','seller_details.city','seller_details.company_telephone','seller_details.about_company','seller_details.company_logo','seller_details.tin_no','seller_details.pan_no','seller_details.address','state.st_name','category.category_name as cat_name')
	->where('user_id',$this->userId)
	->first();

	$userdetail = User::select('email','first_name','last_name','mobileno','roles.name as role_name')
					->join('role_users','role_users.user_id','=','users.id')
					->join('roles','roles.id','=','role_users.role_id')
					// ->where('roles.name','user')

					->where('users.id',$this->userId)->first();
					
					
	return view('vendor.commingsoon',compact('sale_value','sellerdetails','userdetail','totalOrder','noOfProduct','noOfOrder','wtotalOrder','wnoOfProduct','wnoOfOrder'));

  }

  

 public function dashboard()
  {
	$fromdate= date("Y-m-d ",strtotime('monday this week'));
	$todate= date("Y-m-d");
    
	$lmstart = new Carbon('first day of last month');
	$lmend = new Carbon('last day of last month');

	$cmstart = new Carbon('first day of this month');
	$cmend = new Carbon('last day of this month');	
		
		
	$noOfProduct = Products::where('seller_id',$this->userId)
	->count();

	$wnoOfProduct = Products::where('seller_id',$this->userId)
	->where(DB::raw("(date(created_at))"),'>=',$fromdate)
	->where(DB::raw("(date(created_at))"),'<=',$todate)
	->count();
	
	
	//~ echo  $cmstart;
	//~ echo  "<br>";
	//~ echo  $cmend;
	
	//echo  $this->userId;


	$current_month_product = Products::where('seller_id',$this->userId)
	->where('created_at','>=',$cmstart)->where('created_at','<=',$cmend)
	->count();
	
	

	$noOfOrder = Order::
	 Join('order_item','order_item.order_id','=','order.order_id')
	->where('order_item.seller_id',$this->userId)
	->groupby('order.order_id')
	->count();

	$wnoOfOrder = Order::	Join('order_item','order_item.order_id','=','order.order_id')
	->where('order_item.seller_id',$this->userId)
	->where(DB::raw("(date(`tbl_order`.created_at))"),'>=',$fromdate)
	->where(DB::raw("(date(`tbl_order`.created_at))"),'<=',$todate)
	->groupby('order.order_id')
	->count();
	
	
	$monthoOfOrder = Order::
	 Join('order_item','order_item.order_id','=','order.order_id')
	->where('order_item.seller_id',$this->userId)
	->where('order.created_at','>=',$cmstart)->where('order.created_at','<=',$cmend)
	->groupby('order.order_id')
	->count();
	
	

	 $totalOrder = Order::
	  Join('order_item','order_item.order_id','=','order.order_id')
	 ->where('order_item.seller_id',$this->userId)
	 ->groupby('order.order_id')
	 ->sum('grand_total');

	$wtotalOrder = Order::
	Join('order_item','order_item.order_id','=','order.order_id')
	->where('order_item.seller_id',$this->userId)
	->where(DB::raw("(date(`tbl_order`.created_at))"),'>=',$fromdate)
	->where(DB::raw("(date(`tbl_order`.created_at))"),'<=',$todate)
	->groupby('order.order_id')
	->sum('grand_total');
	
	
	$monthtotalOrder = Order::
	Join('order_item','order_item.order_id','=','order.order_id')
	->where('order_item.seller_id',$this->userId)
     ->where('order.created_at','>=',$cmstart)->where('order.created_at','<=',$cmend)
	->groupby('order.order_id')
	->sum('grand_total');
	


	$orders = Order::select(['order.grand_total as grand_total','order.order_id',DB::raw('DATE_FORMAT(tbl_order.created_at, "%d  %M %y ") as created_at'),DB::raw('DATE_FORMAT(tbl_order.created_at,"%Y,%m,%d") as x')])
	->Join('order_item','order_item.order_id','=','order.order_id')
	 ->Join('invoice','invoice.order_id','=','order.order_id')
	 ->where('order_item.seller_id',$this->userId)
	->where(DB::raw("(month(`tbl_order`.created_at))"),'=',date('m'))
	->where(DB::raw("(year(`tbl_order`.created_at))"),'=',date('Y'))
	->groupby('order.order_id')
	->orderBy('order.order_id', 'asc');


	$ordersd = DB::table(DB::raw("({$orders->toSql()}) as pro"))
	->mergeBindings($orders->getQuery())
	->select(DB::raw('sum(grand_total) AS y'),'x')
	->groupby('created_at')
	->get();


	if(isset($ordersd))
	$sale_value = json_encode($ordersd);

	//echo $sale_value;die;

	$sellerdetails	 = SellerDetails::join('state','state.state_id','=','seller_details.state')
	-> join('category','category.id','=','seller_details.category')
	->select('designation','category.category_name','location_on_map','shop_name','first_name','last_name','store_phone_number','mobileno','email','seller_details.bank_account_name','seller_details.account_no','seller_details.ifsc_code','seller_details.branch','seller_details.user_id','seller_details.status','seller_details.company_name','seller_details.city','seller_details.company_telephone','seller_details.about_company','seller_details.company_logo','seller_details.tin_no','seller_details.pan_no','seller_details.address','state.st_name','category.category_name as cat_name')
	->where('user_id',$this->userId)
	->first();

	$userdetail = User::select('email','first_name','last_name','mobileno','roles.name as role_name')
					->join('role_users','role_users.user_id','=','users.id')
					->join('roles','roles.id','=','role_users.role_id')
					// ->where('roles.name','user')

					->where('users.id',$this->userId)->first();
	return view('vendor.sellerdashboard',compact('monthtotalOrder','monthoOfOrder','current_month_product','sale_value','sellerdetails','userdetail','totalOrder','noOfProduct','noOfOrder','wtotalOrder','wnoOfProduct','wnoOfOrder'));

  }

  public function uploadImage()
  {
		$seller = SellerDetails::where('user_id', $this->userId)->first();
		if ($file = Input::file('company_logo')) {
		   
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $folderName = '/uploads/seller/'.$this->userId.'/';
           // @mkdir($folderName);
            $destinationPath = public_path() . $folderName;
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);

            if($seller) {
               if (File::exists(public_path() . $folderName . $seller->company_logo)) {
                File::delete(public_path() . $folderName . $seller->company_logo);
            }
		 }

		   $seller_company = SellerDetails::
		   where('user_id',$seller->user_id)
		    ->update(['company_logo'=>$safeName]);


           return json_encode(array('status'=>'success'));
        }
  }
	public function profile()
	{
		$sellerdetail = User::select('email','first_name','last_name','mobileno')
					   ->where('users.id',$this->userId)->first();
		$user= Sentinel::getUser();
		$news_record= Newsletter::select(['email'])->where('email',$user->email)->first();
		return view('vendor.sellerprofile',compact('sellerdetail','news_record'));
	}
   public function updateSellerProfile(ProfileupdateRequest $request){
		//~ $request->offsetUnset('mobileno');
		//~ $request->offsetUnset('email');
		//~ $request->offsetUnset('_token');
		$input=$request->all();
		                
		           
		$seller_pro_detail= SellerDetails::select(['user_id'])->where('user_id',$this->userId)->first();

		DB::table('users')
		->where('id', $seller_pro_detail->user_id)  // find your user by their email
		->limit(1)  // optional - to ensure only one record is updated.
		->update(array('first_name' => $request->first_name , 'last_name' => $request->last_name ));
									     
		      //~ $val_input['first_name']  =          $request->first_name; 
		      //~ $val_input['last_name']   =          $request->last_name; 
		                
		//echo  "<pre>"; print_r($input); die;  
		
		
		
		//$sellerprofile=User::updateOrCreate(['id' => $seller_pro_detail->user_id],$val_input);
		
		
		
		
		if($seller_pro_detail){
			return redirect('sellerpanel')->with('success', trans('Profile Update Successfully'));
		}
		else {
			return Redirect::route('seller.profile')->withInput()->with('error', trans('Company/message.error.save'));
		}

	}

   public function sellerAccountSetting (){
		return view('vendor.selleraccountsetting');
	}

   public function sellerBankSetting (){
			$sellerdetail =SellerDetails::select('bank_account_name','account_no','ifsc_code','branch')->where('user_id',$this->userId)->first();
			return view('vendor.sellerbanksetting',compact('sellerdetail'));
	}

   public function updateBankSetting(BankSettingRequest $request){
		$input=$request->all();
		$sellerprofile=SellerDetails::updateOrCreate(['user_id' => $this->userId],$input);
		if($sellerprofile){
			return redirect('sellerpanel')->with('success', trans('Bank Detail Update Successfully'));
		}
		else {
			return Redirect::route('seller.banksetting')->withInput()->with('error', trans('Company/message.error.save'));
		}

	}
  public function updatePassword(PasswordupdateRequest $request){

     $input = $request->all();
	 $user= User::select(['id','password','email'])->where('id',$this->userId)->first();

		if(!Hash::check($input['old_password'], $user->password)){
			return Response::json(array('status'=>"error",'success' => false,'message'=> "The specified password does not match with Old password"));
		}
	   else
	   {
			$input['password'] = Hash::make($input['password']);
			$passwordUp=     DB::table('users')
			->where('id', $this->userId)  // find your user by their email
			->limit(1)  // optional - to ensure only one record is updated.
			->update(array('password' => $input['password']));
			$redirect = route('sellerdashboard');
			if($passwordUp){
				return Response::json(array('success' => true,'status' => "success",'redirect_url' => $redirect));

			}

	   }
   }

  public function getLogout()
    {
        Sentinel::logout();
        return Redirect::to('sellerpanel/landing')->with('success', 'You have successfully logged out!');
       
    }

 public function companyProfile()
   {

	  $seller            = SellerDetails::where('user_id', $this->userId)->first();
	  $sellerdetails	 = SellerDetails::join('state','state.state_id','=','seller_details.state')
							 ->join('category','category.id','=','seller_details.category')
							 ->select('first_name','store_phone_number','shop_name','designation','category.category_name','last_name','mobileno','email','seller_details.user_id','seller_details.company_name','seller_details.city','seller_details.company_telephone','seller_details.about_company','seller_details.company_logo','seller_details.tin_no','seller_details.pan_no','seller_details.state','seller_details.postcode','seller_details.address','state.st_name','category.category_name as cat_name','seller_details.location_on_map','seller_details.latitude','seller_details.longitude')
							 ->where('user_id',$this->userId)
							 ->first();
							 
							 
			//echo  "<pre>"; print_r($sellerdetails); die;				 
							 

	 $state = State::orderby('st_name','asc')->get();
	    return view('vendor.company_profile',compact('sellerdetails','seller','state'));

   }

	 public function companyUpdate(SellerCompanyRequest $request)
	 {

		 $seller = SellerDetails::where('user_id', $this->userId)->first();


		  $input=$request->all();

         if ($file = $request->file('company_logo')) {
			 

            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $folderName = '/seller/'.$this->userId.'/';
            $safeName = str_random(10) . '.' . $extension;
            
			@mkdir($folderName,0777,true);
			@chmod($folderName,0777);
				
		    Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);

            if($seller) {
				
				    $folderName = '/seller';
					$filedir = $folderName .'/'. $seller->company_logo;
					Storage::disk('uploads')->delete($filedir);
		     }

            //save new file path into db
            $company_logo = $safeName;
            $input['company_logo']        = $company_logo;
        }


       $seller_company = SellerDetails::updateOrCreate(['user_id' => $seller->user_id], $input);


       if($seller_company)
        {

            return redirect('sellerpanel')->with('success', trans('Comapny Profile Save Successfully'));
        } else {
            return Redirect::route('seller.company')->withInput()->with('error', trans('Company/message.error.save'));
        }




	 }

    public function storeUpdate(SellerStoreRequest $request)
	 {

		 $seller = SellerDetails::where('user_id', $this->userId)->first();


		  $input=$request->all();
		  
		  //~ echo "<pre>"; print_r($input); die;

         if ($file = $request->file('company_logo')) {
			 

            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $folderName = '/seller/'.$this->userId.'/';
            $safeName = str_random(10) . '.' . $extension;
            
			@mkdir($folderName,0777,true);
			@chmod($folderName,0777);
				
		    Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);

            if($seller) {
				
				    $folderName = '/seller';
					$filedir = $folderName .'/'. $seller->company_logo;
					Storage::disk('uploads')->delete($filedir);
		     }

            //save new file path into db
            $company_logo = $safeName;
            $input['company_logo']        = $company_logo;
        }


       $seller_company = SellerDetails::updateOrCreate(['user_id' => $seller->user_id], $input);


       if($seller_company)
        {

            return redirect('sellerpanel')->with('success', trans('Store Profile Save Successfully'));
        } else {
            return Redirect::route('seller.company')->withInput()->with('error', trans('Company/message.error.save'));
        }




	 }


   public function personalUpdate(SellerPersonalRequest $request)
	 {

		 $seller = SellerDetails::where('user_id', $this->userId)->first();


		  $input=$request->all();

   

       $seller_company = SellerDetails::updateOrCreate(['user_id' => $seller->user_id], $input);


       if($seller_company)
        {

            return redirect('sellerpanel')->with('success', trans(' Contact Person Profile Save Successfully'));
        } else {
            return Redirect::route('seller.company')->withInput()->with('error', trans('Company/message.error.save'));
        }




	 }


   //*********************************Seller Login Post function *****************************************//

     public function postLogin(SellerLoginRequest $request)
        {

        $input_email= $request->get('email_login');

	    $userdetail = User::where('email',$input_email)->first();
	    if(empty($userdetail)){


			 return json_encode(array('status'=>"error",'redirect'=>false,'message'=>"You are not registered please signup ."));


			}

	    $seller = SellerDetails::where('user_id', $userdetail->id)->first();

		 if(empty($seller)){
             return json_encode(array('status'=>"error",'redirect'=>false,'message'=>"You are not registered as seller please signup ."));


			}


         $dashboard_url=route("sellerdashboard");
           $credentials = [
                       'email'    => $request->get('email_login'),
                       'password' => $request->get('password_login'),
                       ];

        try {
            // Try to log the user in
            if (Sentinel::authenticate($credentials,0)) {

                 $deleted_user = User::where('email',$request->get('email_login'))->onlyTrashed()->first();

                if($deleted_user){
                      $messageBag ="Your account is deleted, please contact to Admin";
                     return   json_encode(array('status'=>"error",'redirect'=>false,'messageBag'=> $messageBag));
                 }

                 if (Sentinel::check())
                {
                 $this->userId = Sentinel::getUser()->id;
                }



                return json_encode(array('status'=>"succes",'redirect'=>true,'message'=>"Login Succesfully",'redirect_url'=>$dashboard_url ));

            } else {

                   return json_encode(array('status'=>"error",'redirect'=>false,'message'=>"Email/Mobile No or password is incorrect."));

            }

        } catch (UserNotFoundException $e) {
          //  $this->messageBag->add('email', Lang::get('auth/message.account_not_found'));
              $messageBag =    Lang::get('auth/message.account_not_found');
        } catch (\Cartalyst\Sentinel\Checkpoints\NotActivatedException $e) {
              // $messageBag =     $this->messageBag->add('email', Lang::get('auth/message.account_not_activated'));
            $messageBag =     "Your email is not verified yet, please check your mailbox to verify email";
        } catch (\Cartalyst\Sentinel\Checkpoints\UserSuspendedException $e) {
           // $this->messageBag->add('email', Lang::get('auth/message.account_suspended'));
            $messageBag =     Lang::get('auth/message.account_suspended');

        } catch (\Cartalyst\Sentinel\Checkpoints\UserBannedException $e) {
               $messageBag =     Lang::get('auth/message.account_banned');
            //$this->messageBag->add('email', Lang::get('auth/message.account_banned'));
        } catch (\Cartalyst\Sentinel\Checkpoints\ThrottlingException $e) {
           $delay = $e->getDelay();
           // $this->messageBag->add('email', Lang::get('auth/message.account_suspended', compact('delay')));
             $messageBag = Lang::get('auth/message.account_suspended', compact('delay'));
        }

        // Ooops.. something went wrong
         return   json_encode(array('status'=>"error",'redirect'=>false,'messageBag'=> $messageBag));

        }




     public function postLogin11(SellerLoginRequest $request)
    {

		$input_email= $request->get('email_login');

	    $userdetail = User::where('email',$input_email)->first();
	    if(empty($userdetail)){
			 return Redirect::route('seller.login')->with('error', 'You have not registred please signup .');

			}

	    $seller = SellerDetails::where('user_id', $userdetail->id)->first();

		 if(empty($seller)){

			 return Redirect::route('seller.login')->with('error', 'You have not registred please signup .');

			}


		 $credentials = [
	  	 			'email'    => $request->get('email_login'),
	  	 			'password' => $request->get('password_login'),
	  	 			];

        try {
            // Try to log the user in
            if (Sentinel::authenticate($credentials,0)) {


                return Redirect::route("sellerdashboard")->with('success', Lang::get('auth/message.login.success'));
            } else {

                return Redirect::route('seller.login')->with('error', 'Email/Mobile No or password is incorrect.');
                //return Redirect::back()->withInput()->withErrors($validator);
            }

        } catch (\Cartalyst\Sentinel\Checkpoints\UserNotFoundException $e) {
            $this->messageBag->add('email', Lang::get('auth/message.account_not_found'));
        } catch (\Cartalyst\Sentinel\Checkpoints\NotActivatedException $e) {
            $this->messageBag->add('email', Lang::get('auth/message.account_not_activated'));
        } catch (\Cartalyst\Sentinel\Checkpoints\UserSuspendedException $e) {
            $this->messageBag->add('email', Lang::get('auth/message.account_suspended'));
        } catch (\Cartalyst\Sentinel\Checkpoints\UserBannedException $e) {
            $this->messageBag->add('email', Lang::get('auth/message.account_banned'));
        } catch (\Cartalyst\Sentinel\Checkpoints\ThrottlingException $e) {
            $delay = $e->getDelay();
            $this->messageBag->add('email', Lang::get('auth/message.account_suspended', compact('delay')));
        }

        // Ooops.. something went wrong
            return Redirect::route('seller.login')->with('error', $this->messageBag);
        return Redirect::back()->withInput()->withErrors($this->messageBag);
	}

     public function getActivate($userId,$activationCode = null)
    {


        $user = Sentinel::findById($userId);


         $Acticarion_record = DB::table('activations')->where('user_id',$userId)->where('completed',"1")->get();

      if(empty( $Acticarion_record)){
        $activation = Activation::create($user);

        if (Activation::complete($user, $activation->code))
        {


            // Activation was successful
            // Redirect to the login page
            return Redirect::route('seller.login')->with('success', Lang::get('auth/message.activate.success'));
        }
        else
        {

            // Activation not found or not completed.
            $error = Lang::get('auth/message.activate.error');
            return Redirect::route('seller.login')->with('error', $error);
        }
        }
        else{
			return Redirect::route('seller.login')->with('success', Lang::get('Account already activated'));;

			}

    }




   //*********************** admin panel functions ************************//


   public function sellerIndex()
   {
	   $users = User::join('role_users','role_users.user_id','=','users.id')
	                    ->join('roles','roles.id','=','role_users.role_id')
	                    ->join('seller_details','seller_details.user_id','=','users.id')
	                     ->select('users.id','users.email','users.created_at as registr_date','seller_details.company_name','seller_details.company_telephone','seller_details.status',DB::raw("(DATE_FORMAT(`tbl_seller_details`.created_at,$this->date)) as add_date"))
	                     ->where('roles.name','Seller')->get();



	    return view('admin.seller.index', compact('users'));

	 }


      public function approve(SellerStatusRequest $request, $company)
      {

		 $seller = SellerDetails::where('slug',$company)->first();

		 $user = Sentinel::findById($seller->user_id);


         if(!empty($seller))
         {

				$datenow =   Carbon::now();

				$sellerap['status'] = $request->status;
				$sellerap['auto_approval'] = $request->auto_approval;
				$sellerap['commission'] = $request->commission;
				$sellerap['approve_date'] =  $datenow;

			    $seller_company = SellerDetails::updateOrCreate(['user_id' => $seller->user_id], $sellerap);

			  $noti = new Notification();
			  $noti->user_id   =    $seller->user_id;
			  $noti->message   = $request->get('message');
			  $noti->type      = "seller";

			  $noti->save();


			 $data = array(
			 'user'    =>  $user->first_name,
			 'useremail'    =>  $user->email,
	     	 'status'    => $request->status,
             'message'  => $request->get('message'),

                   );

		
	      }
		else
		{
			return Response::view('404', array(), 404);
		}


			return(json_encode(
				array(
				'success' => true,
				'status' => "success"

				)));


	  }

	  public function changePassword(SellerPassRequest $request,$company)
	  {
	    if (Request::ajax()) {

            $seller = SellerDetails::where('slug',$company)->first();


            $user = Sentinel::findById($seller->user_id);



            $password = Request::get('password');

            $user->password = Hash::make($password);
            $user->save();


            return(json_encode(
				array(
				'success' => true,
				'status' => "success"

				)));

        }


		  }
   public function sellerShow($id)
    {

        try {
            // Get the user information
            $user = Sentinel::findUserById($id);

            //
            if( empty($user))
            {
				//echo "sas"; die;
				 $error = Lang::get('Seller does not exist.', compact('id'));
                      return Redirect::route('sellers')->with('error', $error);

				}
			$sellerdetails = SellerDetails::where('user_id',$user->id)->first();
			if(empty($sellerdetails)){

					 $error = Lang::get('Seller does not exist. ', compact('id'));
                      return Redirect::route('sellers')->with('error', $error);


					}

            $seller = SellerDetails::join('state','state.state_id','=','seller_details.state')->where('user_id', $user->id)->first();
            //get country name


        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = Lang::get('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('admin.users.index')->with('error', $error);
        }


        // Show the page
        return View('admin.seller.show', compact('user','seller','selleDetails'));

    }

   
	public function newsletterPreference(){
		$user= Sentinel::getUser();
		$news_record= Newsletter::select(['email'])->where('email',$user->email)->first();
		$new =  Input::get('newsletter');

		if(empty($news_record)){
			if( $new =="Yes"){
				$is_deleted= Newsletter::select(['news_id','email'])->where('email',$user->email)->onlyTrashed()->first();
				if(!empty($is_deleted)){
					Newsletter::withTrashed()->find($is_deleted->news_id)->restore();
				}
				else{
					$email= new Newsletter();
					$email->email=$user->email;
					$email->save();
				}
			}

		}
		if(!empty($news_record))
		    {
		         if( $new =="No"){
					 $news_record= DB::table('newsletter')->where('email',$user->email)->delete();
		   	      }
		     }


		return Response::json(array('success' => true,'status' => "success"));
	}
	
	
	public function user_info($id){
		//echo "done"; die;
		$PARENT_ID=1;
		
		$detail=User::select(['users.full_name','users.first_name','users.last_name','users.email','roles.name as role','users.mobileno','users.address'
			,DB::raw("(DATE_FORMAT(`tbl_users`.created_at,'%d %M  %Y')) as add_date"),'users.id'])
			->join('role_users','role_users.user_id','=','users.id')
			->join('roles','roles.id','=','role_users.role_id')
			->where('users.id',$id)
			->first();
			
			//echo $detail; die; 
		
		if(count($detail)==0){
		$notification = array(
			'message' =>  'Sorry User Detail Not Found.', 
			'alert-type' => 'warning'
			);
			return redirect('sellerpanel')->with($notification);
		}
		return view('vendor.userinfo',compact('PARENT_ID','detail'));
	}

	public function item_info($id = null)
	{
			
			$order = Order::select('shipment_status','discount_amount','delivery_time','order_address','order_currency_code','delivery_option','shipping_amount'
			,'sub_total','order.created_at','increment_id','grand_total','order.order_id','order.status'
			,DB::raw("(DATE_FORMAT(tbl_order.created_at,'%d %M  %Y')) as add_date")
			)
			->where('order.order_id',$id)
			->first();

		//echo  "<pre>"; print_r($order); die;
			
		if(count($order)>0)
		{
		$items  =	OrderItem::select('order_item.product_name','row_total','order_item.quantity_order','unit','order_item.product_id','order_item.price','seller_details.company_name')
			->join('seller_details','seller_details.user_id','order_item.seller_id')
			->where('order_item.order_id',$id)
			->where('order_item.seller_id',Sentinel::getUser()->id)
			->get();
			
			
			
			
			
		
		}else{
			
			echo  "Order Not Found"; die;
			
			
		}
		
		
		
		
		
		
			return view('vendor.iteminfo',compact('PARENT_ID','order','items'));
	}
	
	
	public function getModalComplete($id = null)
    {
		$model = 'Confirm Order';
		$confirm_route = $error = null;
		$message = 'Are you sure to Confirm Order for this record';
		$confirm_route = route('seller.complete.order.view', ['id' => $id]);
		
		
		
		return View('vendor/confirm_order_modal', compact('error', 'model', 'confirm_route','message'));
    }
    
 
    
      public function getModalCompleteView($id = null)
    {
		$model = 'Confirm Order';
		$confirm_route = $error = $message = null;
		
		
		
		 $orderS =  OrderStatus::where('order_id',$id)->where('seller_id',Sentinel::getUser()->id )
								  ->where('order_status.status','pending')
								  ->first();
		 
		 
		 if(! $orderS){
			 $error = 'Order Not Found';
			 
		 }else{
			$message = 'Are you sure to Confirm Order for this record';
			$confirm_route = route('seller.complete.order.view.post', ['id' => $id]);
			
			
		//	echo   $confirm_route; die;
			
			
			$order = Order::select('shipment_status','discount_amount','delivery_time','order_address','order_currency_code','delivery_option','shipping_amount'
			,'sub_total','order.created_at','increment_id','grand_total','order.order_id','order.status'
			,DB::raw("(DATE_FORMAT(tbl_order.created_at,'%d %M  %Y')) as add_date")
			)
			->where('order.order_id',$id)
			->first();

			//echo  "<pre>"; print_r($order); die;

			if(count($order)>0)
			{
			$items  =	OrderItem::select('order_item.product_name','ord_item_id','row_total','order_item.quantity_order','unit','order_item.product_id','order_item.price','seller_details.company_name')
			->join('seller_details','seller_details.user_id','order_item.seller_id')
			->where('order_item.order_id',$id)
			->where('order_item.seller_id',Sentinel::getUser()->id)
			->get();


           return View('vendor/confirm_order_view', compact('error', 'model', 'confirm_route','message','order','items'));


			}else{

			echo  "Order Not Found"; die;


			}
			 
		 }
		
		
		
		return View('vendor/confirm_order_view', compact('error', 'model', 'confirm_route','message'));
    }
    
    public function CompleteOrderPost(valRquest $request, $id)
	{
		
		 $this->validate($request, [
                     'delivery_time' => 'required|min:3',
              
          ]);
          
         $order_action  =$request->get('order_action');
         $delivery_time  =$request->get('delivery_time');
         
          $orderS =  OrderStatus::select('seller_details.shop_name')				
								->join('seller_details','seller_details.user_id','order_status.seller_id')
								->where('order_id',$id)->where('seller_id',Sentinel::getUser()->id )
								->where('order_status.status','pending')
								->first();
								  
								  $orderData = Order::select('member_id')->where('order_id',$id)->first();
								  
		if($orderS) { 	
			
			$item_count =  		count($order_action);
			
			//echo  $item_count;
			
			$accept = 0;		  
								  
         foreach($order_action as $key  =>  $value){
			 
			if($value == 'Item Ready to Ship')
			$accept =  $accept+1;
			 
			   OrderItem::where('order_id', $id)->where('ord_item_id', $key)->where('seller_id', Sentinel::getUser()->id )->update(['order_status' =>$value ]);
		  }
		  
		 $push = array();
			if($accept > 0){
				$order_S['delivery_time'] = $delivery_time;
				$order_S['status'] = 'awaiting shipment';
				$order_S['state'] = 'awaiting shipment';
				

		      $push['status']	      = 'Your Order from '.$orderS->shop_name.' is being collected and It will reach on your door step around '.$delivery_time;

			}else{

				$order_S['comment']  =   'Item not available';
				$order_S['reason']   =   'Item not available';
				$order_S['status']   =   'failed';
				$order_S['state']    =   'failed';
				
			 $push['status']	      = 'Your Order from '.$orderS->shop_name.' has been  cancelled !';

			}
           
           
			
			$push['order_id'] 		= 1;
			$push['receiver_id']	= $orderData->member_id;
			$push = (object)$push;
			PushNotification::send('test_push', $push);
        
        
        
           OrderStatus::updateOrCreate(['order_id' =>$id, 'seller_id' => Sentinel::getUser()->id ],$order_S);
          
          
          
                $output['msg']				= "Action performed Successfully.";
				$output['message']			= "Action performed Successfully.";
				$output['msgHead']			= "Success ! ";
				$output['msgType']			= "success";
				$output['status']			= 'success';
				$output['resetform']	    = true;
				$output['slideToTop']		= true;
				$output['url']		        = URL::to('sellerpanel/orders/pending-orders');
				
				return json_encode($output);
				
			 }else{
				 
				 
				 echo  "Order not found";
				 }
          
          //~ $input=$request->all();
          
         // echo  "<pre>"; print_r($input); die;
          
	  }
    
	 public function getModalCancel($id = null)
    {
		$model = 'Order Cancel/Fail/Decline';
		$confirm_route = $error = null;
		$message = 'Are you sure to Cancel/Fail/Decline for this order';
		$confirm_route = route('seller.do-cancel.order', ['id' => $id]);
		return View('vendor/fail-confirm-order', compact('error', 'model', 'confirm_route','message'));
    }
	
	public function complete_order($id)
	{
		//echo "done"; die;
		
		$query = OrderItem::select('order_item.seller_id','order_item.order_id','order.increment_id','order_item.member_id','order_item.quantity_order','order_item.base_price','order.status','order_item.row_total','order.payment_method','order.grand_total','users.full_name');
				
		$query->where('order_item.order_status','pending');
		
		$order = $query->join('users','users.id','order_item.member_id')
		->join('order','order.order_id','order_item.order_id')
		->where('order_item.seller_id',Sentinel::getUser()->id)
		->where('order_item.ord_item_id',$id)
		->first();
		
		if(count($order) > 0)
		{
			OrderItem::where('ord_item_id',$id)->update(['order_status'=>'completed']);
		}
		
		$notification = array(
				'message' =>  'Payment made successfully.', 
				'alert-type' => 'success'
		);
		return redirect('sellerpanel/orders/pending-orders')->with($notification);
			
	}
	
	public function cancel_order(valRquest $request, $id)
	{
		
		 $this->validate($request, [
                     'reason' => 'required',
              
          ]);
          
          
		$state   = 'failed';
		$comment = 'failed';
	
	
		$order['state']    =   $state;
		$order['status']   =   $state;
		$order['comment']  =   $request->get('comment');
		$order['reason']   =   $request->get('reason');
		
		
        $orderS =  OrderStatus::join('seller_details','seller_details.user_id','order_status.seller_id')
                                  ->select('seller_details.shop_name','order_status.status')
                                   ->where('order_id',$id)
                                   ->where('seller_id',Sentinel::getUser()->id )
								   ->where('order_status.status','pending')
								   ->first();
								  
		$orderData = Order::select('member_id')->where('order_id',$id)->first();
		$push = array();
		$push['status']	      = 'Your Order from '.$orderS->shop_name.' has been cancelled due to '.$request->get('reason');
		$push['order_id'] 		= 1;
		$push['receiver_id']	= $orderData->member_id;
		$push = (object)$push;  
		PushNotification::send('order_push', $push);						  
								  
								  
								  
		if($orderS) { 		
		
		OrderStatus::updateOrCreate(['order_id' =>$id, 'seller_id' => Sentinel::getUser()->id ],$order);
		OrderItem::where('order_id', $id)->where('seller_id', Sentinel::getUser()->id )->update(['order_status' =>$state ]);


			//~ $orderstatus = new OrderStatus();
			//~ $orderstatus->state=$state;
			//~ $orderstatus->order_id=$id;
			//~ $orderstatus->status=$state;
			//~ $orderstatus->comment=$comment;

			//~ $orderstatus->save();
			
				$output['msg']				= "Order Cancelled Successfully.";
				$output['message']				= "Order Cancelled Successfully.";
				$output['msgHead']			= "Success ! ";
				$output['msgType']			= "success";
				$output['status']			= 'success';
				$output['resetform']	    = true;
				$output['slideToTop']		= true;
				$output['url']		        = URL::to('sellerpanel/orders/pending-order');
				
				return json_encode($output);
				
			}else{
				
				echo  "Order Not Found"; die;
				}
				
        	
			
	}
	



}
