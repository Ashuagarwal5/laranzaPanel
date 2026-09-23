<?php
namespace App\Http\Controllers\vendor;
use App\Http\Controllers\CodespurController;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

use Illuminate\Http\Request;
use App\Http\Requests\OderRequest;
use App\Http\Requests\OrderstatusRequest;
use App\Http\Requests\OderShipmentRequest;

use App\Order;
use App\OrderStatus;
use App\OrderItem;
use App\Orderaddress;
use App\Invoice;
use App\OrderPayment;
use App\CourierService;
use App\OrderShipment;



use Lang;
use Redirect;
use Sentinel;
use View;
use DB;
use Auth;
use Arrays;
use Response;
use Datatables;
use Mail;
use Session;


class SellerOrdersController extends CodespurController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
     var $orderstatus='';

  
	function __construct()
	{
		if (Sentinel::check())
		{ 
			$this->userId = Sentinel::getUser()->id;
		}
	}

    public function index($slug = '')
    {
 
        if($slug!=""){
        
        $this->output['submenu']=ucfirst($slug);
         $this->output['slug']=$slug;
	      }
        else{
		
        $this->output['submenu']="";
        $this->output['slug']="";
	        }
      
        return view('vendor.order.orderlist',$this->output);
    }

  
	public function data()
	{
		$orders = Order::select([DB::raw('sum(`tbl_order_item`.rowtotal_includetax) AS item_total'),'order.status','order.member_id','invoice.invoice_id','order_payment.payment_method as method','order.order_id','order.grand_total',DB::raw('CONCAT(tbl_order_address.first_name, " ", tbl_order_address.last_name) AS shipping_name'),DB::raw('DATE_FORMAT(tbl_order.created_at, "%d  %M %y - %h:%i %p ") as order_date') ])
		->Join('order_item','order_item.order_id','=','order.order_id')
		->leftJoin('order_address','order_address.order_id','=','order.order_id')
		->leftJoin('order_payment','order_payment.order_id','=','order.order_id')
		->leftJoin('invoice','invoice.order_id','=','order.order_id')
		->where('order_item.seller_id',$this->userId)
		->groupby('order.order_id')
		->orderBy('order.order_id', 'desc');

		return Datatables::of($orders)
		->make(true);		

	}
    
    
	public function customerhistory($customer_id)
	{
		$orders = Order::select(['order.status','invoice.invoice_id','order_payment.payment_method as method','order.order_id','order.grand_total',DB::raw('CONCAT(tbl_order_address.first_name, " ", tbl_order_address.last_name) AS shipping_name'),DB::raw('DATE_FORMAT(tbl_order.created_at, "%d  %M %y - %h:%i %p ") as order_date') ])
		->leftJoin('order_address','order_address.order_id','=','order.order_id')
		->Join('order_item','order_item.order_id','=','order.order_id')
		->leftJoin('order_payment','order_payment.order_id','=','order.order_id')
		->leftJoin('invoice','invoice.order_id','=','order.order_id')
		->where('order_item.seller_id',$this->userId)
		->where('member_id',$customer_id)
		->groupby('order.order_id')
		->orderBy('order.order_id', 'desc')
		->get();

		return Datatables::of($orders)
		->make(true);
	}

    public function show($entity_id)
    {

      	$order = Order::join('order_address', 'order_address.order_id', '=', 'order.order_id')
      	->Join('order_item','order_item.order_id','=','order.order_id')
								  ->join('state', 'state.state_id', '=', 'order_address.region_id')
						          ->leftjoin('order_payment', 'order_payment.order_id', '=', 'order.order_id')
								  ->where('order.order_id',$entity_id)
								  ->select('order.order_id','order.member_id','order.grand_total','order.sub_total','order.status','order.state','order.discount_amount','order.tax_amount','order_address.first_name','order_address.last_name','order.created_at','order_address.pincode','order_address.email','order_address.phone','order_address.address','order_address.city','state.st_name','order_payment.payment_method')
								  ->where('order_item.seller_id',$this->userId)
								  ->groupby('order_address.order_id')
								  
	                              ->first(); 
	  
	   $OrderProduct=OrderItem::where('order_id',$entity_id)
			                      ->select('product_name','quantity_order','orignal_price','base_price','price','tax_amount','row_total','rowtotal_includetax','tax_percentage')
			                       ->where('seller_id',$this->userId)
			                      ->get();	
			                      
	   $invoice = Invoice::where('order_id',$entity_id)->first();
	           
       $payment = OrderPayment::where('order_id',$entity_id)
		                          ->select('ord_pay_id','total_amount','payment_method')
		                           ->first(); 
		                           
	  $couriernames = CourierService::select('courier_name','cor_srv_id')
	                                    ->get();                        
		                           
	   $remaining_state =  $this->getOrderState($order->state);  
		                                 
       return view('vendor.order.show',compact('order','OrderProduct','remaining_state','invoice','payment','couriernames'));
    }
    
    public function getOrderState($state)
     {
		$all_state  =  config('constants.Orderstatus');
		
		foreach($all_state as $key => $value)
		{
			
			if($key == $state){
				break;
				}
			else{
				unset($all_state[$key]); 
				}
		}
	
		return $all_state;
	}
     
    public function ordercomment($order_id)
    {
  
	    $comments =OrderStatus::where('order_id',$order_id)->orderBy('ord_status_id', 'desc')->get();
        $all_state  =  config('constants.Orderstatus');
    
	    return view('vendor.order.comment',compact('comments',' $all_state'));
   }
   
   
     public function order_status($order_id)
   {
	   
	    $Order=Order::select('order.created_at as order_date','order.grand_total','order.order_currency_code','order.status','order_payment.payment_method','invoice.created_at as awaiting_shipment_date')
		    ->leftJoin('invoice','invoice.order_id','=','order.order_id')
		    
		    ->join('order_payment','order_payment.order_id','=','order.order_id')
	    	->where('order.order_id',$order_id)->first();
	    	
	    	
	    $Order->shipment = OrderShipment::where('order_id',$order_id)
			->select('created_at as dispach_date')->get();	
	    
	    return view('vendor.order.statusdata',compact('Order'));
	    	
	    	
	   
	}
	
   public function getstate($state)
    {
		  $all_status =  config('constants.Orderstatus.'.$state);
		
		  return json_encode($all_status);
		
	}
	
	
	 public function statedata($slug=null)
   {
	
		if($slug == 'awaiting_shipment'){


			$orders = Order::select([DB::raw('sum(`tbl_order_item`.rowtotal_includetax) AS item_total'),'order.status','order.member_id','order_payment.payment_method as method', 'invoice.invoice_id','order.order_id','order.grand_total',DB::raw('CONCAT(tbl_order_address.first_name, " ", tbl_order_address.last_name) AS shipping_name'),DB::raw('DATE_FORMAT(tbl_order.created_at, "%d  %M %y - %h:%i %p ") as order_date') ])
		
			->leftJoin('order_address','order_address.order_id','=','order.order_id')
			->leftJoin('order_payment','order_payment.order_id','=','order.order_id')
			->leftJoin('invoice','invoice.order_id','=','order.order_id')
			->Join('order_item','order_item.order_id','=','order.order_id')
			->where('order.status',"awaiting shipment")
			->where('order_item.seller_id',$this->userId)
			->groupby('order.order_id')
			->orderBy('order.order_id', 'desc');;

		}
		else{
			$orders = Order::select([DB::raw('sum(`tbl_order_item`.rowtotal_includetax) AS item_total'),'order.status','order.member_id','order_payment.payment_method as method', 'invoice.invoice_id','order.order_id','order.grand_total',DB::raw('CONCAT(tbl_order_address.first_name, " ", tbl_order_address.last_name) AS shipping_name'),DB::raw('DATE_FORMAT(tbl_order.created_at, "%d  %M %y - %h:%i %p ") as order_date') ])
			
			->leftJoin('order_address','order_address.order_id','=','order.order_id')
			->leftJoin('order_payment','order_payment.order_id','=','order.order_id')
			->leftJoin('invoice','invoice.order_id','=','order.order_id')
			->Join('order_item','order_item.order_id','=','order.order_id')
			->where('order.state',$slug)
			->where('order_item.seller_id',$this->userId)
			->groupby('order.order_id')
			->orderBy('order.order_id', 'desc');;
		}						   
			return Datatables::of($orders)
			->make(true);				   
							   
	}
	
    public function updatestatus($order_id,OrderstatusRequest $request)
    {
	   
	       $invoice = Invoice::where('order_id',$order_id)->first();
	       
	      //~ if( empty($invoice) and $request->get('status') == 'awaiting shipment') {
	  	      //~ echo "<script>alert('Order Invoice Not Generated Yet');</script>";
	  	      //~ 
		  //~ }
	  	      
	  	 
	     	
		   
		    $order_status = new OrderStatus();
		    $order_status->status = $request->get('status');
		    $order_status->state = $request->get('state');
		    $order_status->comment = $request->get('comment');
		    $order_status->order_id = $order_id;
			$save_status = $order_status->save();
			
		    $ord['status'] = $request->get('status');
		    $ord['state']    = $request->get('state');
		    $order_state   = Order::updateOrCreate(['order_id' => $order_id],$ord);  
	       
	        $notify =	$request->get('notify');
	     	 if($notify){
			$Orderaddress=Orderaddress::where('order_id',$order_id)
			                      ->select('first_name','last_name','email')
			                      ->first();
			 $OrderProduct=OrderItem::where('order_id',$order_id)
			                      ->select('product_name','quantity_order','orignal_price','base_price','price','tax_amount','row_total','rowtotal_includetax','tax_percentage')
			                      ->get();	
			   $order = Order::where('order.order_id',$order_id)
								  ->select('order.order_id','order.grand_total','order.sub_total','order.status','order.state','order.discount_amount','order.tax_amount')
								  ->groupby('order.order_id')
	                              ->first();                                          	
		
	     	 $data = array(
	     	 'user' => $Orderaddress['first_name'],
            'status' => $request->get('status'),
            'state' => $request->get('state'),
            'comment' => $request->get('comment'),
           
                   );

			   Mail::send('emails.orderstatus', compact('data','OrderProduct','order'), function ($m) use ($data,$Orderaddress) {
            
           
            $m->from('info@bazaarmitragifts.com',@trans('BazaarMitra.com') );
            $m->to($Orderaddress['email'], $Orderaddress['first_name'] . ' ' . $Orderaddress['last_name']);
            $m->subject('Welcome ' . $Orderaddress['first_name']);
                  });
		      }
		 
	        if($save_status and   $order_state ){
	       		return Response::json(array('success' => true,'status' => "success",'data' => $order_status));     
		    }
		   
	}
	
	
	public function generate_invoice($order_id)
	{
		
		$order = Order::where('order_id',$order_id)
							->select('order_id','grand_total','address_id','order_currency_code','total_item','shipping_tax_amount','shipping_amount','sub_total','status','state','discount_amount','tax_amount')
							->groupby('order_id')
							->first();
						
		
		$order_invoice = new Invoice();
		$order_invoice->order_id              =$order->order_id;
		$order_invoice->grand_total            =$order->grand_total;
		$order_invoice->discount_amount        =$order->discount_amount;
		$order_invoice->sub_total              =$order->sub_total;
		$order_invoice->shipping_amount        =$order->shipping_amount;
		$order_invoice->shipping_tax_amount    =$order->shipping_tax_amount;
		$order_invoice->tax_amount             =$order->tax_amount;
		$order_invoice->billing_address_id     =$order->address_id;
		$order_invoice->currency               =$order->order_currency_code;
		$order_invoice->total_quantity         =$order->total_item;
		$order_invoice->subtotal_include_tax   = $order->sub_total + $order->tax_amount;
		$order_invoice->shipping_include_tax   =$order->shipping_amount + $order->shipping_tax_amount ;
		
		$invoicesave   = $order_invoice->save(); 					
							
		$ord['status']   = "awaiting shipment";
		$ord['state']    = "processing";
		$order_state     = Order::updateOrCreate(['order_id' => $order_id],$ord);  				
		
		
		
	    $order_status = new OrderStatus();
	    $order_status->status      = "awaiting shipment";
		$order_status->state       = "processing";
		$order_status->comment     = "Invoice Generated";
		$order_status->order_id    = $order_id;
	    
		$save_status = $order_status->save();
		
		
		
		
		
		
							
         if($invoicesave and   $order_state and $save_status ){
	       		return Response::json(array('success' => true,'status' => "success"));     
		    }                
		
    }
   
   public function email_invoice($order_id)
   {
	  
	 
	  $Order=Order::select('order.order_id','invoice.invoice_id','order.created_at','order.tax_amount','order.sub_total','order.grand_total','order.shipping_amount','order.shipping_method','order_payment.payment_method','order_payment.payment_method')
		  ->leftJoin('invoice','invoice.order_id','=','order.order_id')
		->join('order_payment','order_payment.order_id','=','order.order_id')
		->where('order.order_id',$order_id)->first();
		
	
		$Order->itemDetail=Order::select('order_item.product_option','order_item.product_name','order_item.product_sku','order_item.row_total','order_item.quantity_order','order_item.price_include_tax','order_item.rowtotal_includetax')->
		join('order_item','order_item.order_id','=','order.order_id')->
		where('order.order_id',$Order->order_id)->get();
		
		$Order->address = Orderaddress::
			join('state', 'state.state_id', '=', 'order_address.region_id')
			->join('country', 'country.cntry_id', '=', 'order_address.country_id')
			->select('order_address.first_name','order_address.last_name','order_address.pincode','order_address.email','order_address.phone','order_address.address','order_address.city','state.st_name')
			->where('order_address.order_id',$Order->order_id)
			->first();  
	   
	   Mail::send('emails.invoice_email', compact('Order'), function ($m) use ($Order) {


		$m->from('info@bazaarmitragifts.com',@trans('BazaarMitra.com') );
		$m->to($Order->address->email, $Order->address->first_name . ' ' . $Order->address->last_name);
		$m->subject('Bazaarmitra Invoice ' . $Order->address->first_name);
		});
		
	  return Response::json(array('success' => true,'status' => "success"));      
	   
	   
	   }
   public function invoice($order_id)
    {
           
          $order = Order::join('order_address', 'order_address.order_id', '=', 'order.order_id')
								  ->join('state', 'state.state_id', '=', 'order_address.region_id')
								  ->where('order.order_id',$order_id)
								  ->select('order.order_id','order.grand_total','order.sub_total','order.order_currency_code','order.shipping_tax_amount','order.shipping_amount','order.status','order.state','order.discount_amount','order.tax_amount','order_address.first_name','order_address.last_name','order.created_at','order_address.pincode','order_address.email','order_address.phone','order_address.address','order_address.city','state.st_name')
								  ->groupby('order_address.order_id')
	                              ->first(); 
         
		  $invoice        = Invoice::where('order_id',$order_id)->first();
		  $OrderProduct   = OrderItem::where('order_id',$order_id)
			                      ->select('product_name','quantity_order','orignal_price','base_price','price','tax_amount','row_total','rowtotal_includetax','tax_percentage')
			                      ->get();	
		
		  $payment = OrderPayment::where('order_id',$order_id)
		                          ->select('ord_pay_id','total_amount','payment_method')
		                           ->first(); 
		                  
		  return view('vendor.order.invoicelist',compact('order','invoice','payment','OrderProduct'));
                      
    }
 
 
 public function shipment($order_id,OderShipmentRequest $request)
 {
	 
	    //$ship['courier_name']               = $request->courier_name;
		//$ship['tracking_detail']            = $request->tracking_detail;
	    //$ship['order_id']                   = $order_id;
	     $ship['is_mail']                    = $request->is_mail;
	  
	 
	  
	    $order_shipment = new OrderShipment();
	    $order_shipment->courier_name = $request->courier_name;
	    $order_shipment->tracking_detail = $request->tracking_detail;
	    $order_shipment->order_id = $order_id;
	    $order_shipment->seller_id = $this->userId;
	    $order_shipment->is_mail = $request->is_mail;
		$ship_save = $order_shipment->save();
		  $insertedId = $order_shipment->ord_shp_id;
		
		
		 if( $ship['is_mail']=='Yes'){
		
		
		  $Order=Order::select('order.order_id','order.created_at','order.tax_amount','order.sub_total','order.grand_total','order.shipping_amount','order.shipping_method','order_payment.payment_method','order_payment.payment_method')
		->join('order_payment','order_payment.order_id','=','order.order_id')
		->where('order.order_id',$order_id)->first();
		
		
		$Order->itemDetail=Order::select('order_item.product_option','order_item.product_name','order_item.product_sku','order_item.row_total','order_item.quantity_order','order_item.price_include_tax','order_item.rowtotal_includetax')->
		join('order_item','order_item.order_id','=','order.order_id')->
		where('order.order_id',$Order->order_id)->get();
		
		$Order->address = Orderaddress::
			join('state', 'state.state_id', '=', 'order_address.region_id')
			->join('country', 'country.cntry_id', '=', 'order_address.country_id')
			->select('order_address.first_name','order_address.last_name','order_address.pincode','order_address.email','order_address.phone','order_address.address','order_address.city','state.st_name')
			->where('order_address.order_id',$Order->order_id)
			->first();
			
		
	   $Order->shipment = OrderShipment::where('ord_shp_id',$insertedId)
			->select('ord_shp_id','courier_name','tracking_detail')->first();
		
	
			
			
		Mail::send('emails.shipment', compact('Order'), function ($m) use ($Order) {


		$m->from('info@bazaarmitragifts.com',@trans('BazaarMitra.com') );
		$m->to($Order->address->email, $Order->address->first_name . ' ' . $Order->address->last_name);
		$m->subject('Bazaarmitra  : Shipment #'.$Order->shipment->ord_shp_id .' '.'for Order '.'#'.$Order->order_id);
		});
		  }
	  
	  
	 	$ord['status']   = "dispatched";
		$ord['state']    = "complete";
		
		$order_state     = Order::updateOrCreate(['order_id' => $order_id],$ord);  				
		
		
		
	    $order_status = new OrderStatus();
	    $order_status->status  = "dispatched";
	    $order_status->state  = "complete";
	    $order_status->comment  = "Order Shipped";
	    $order_status->order_id  = $order_id;
		$save_status = $order_status->save();
		
		
	  if($ship_save){
	       		return Response::json(array('success' => true,'status' => "success"));     
		    }                
		
	   
  }
   
  public function track_shipment($order_id)
  {
	  $track = OrderShipment::select(['ord_shp_id','courier_name','tracking_detail',DB::raw('DATE_FORMAT(created_at, "%d,  %M %Y") as add_date')])
	  ->where('seller_id',$this->userId)
	  ->where('order_id',$order_id)->orderBy('ord_shp_id', 'desc');
      return Datatables::of($track)
              ->make(true);	
  } 
  
   
}
