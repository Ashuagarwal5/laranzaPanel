<?php
namespace App\Http\Controllers\vendor;
use App\Http\Controllers\CodespurController;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Http\Request;
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
use App\Order;
use App\OrderItem;
use App\Helpers\datehelper;
use Carbon\Carbon;
class SellerEarningController extends CodespurController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
     
	function __construct()
	{
		$this->date=datehelper::dateformat();
		if (Sentinel::check())
		{ 
			$this->userId = Sentinel::getUser()->id;
		}
	}

    public function index($month=null,$year=null)
    {
	    
			if($month!=null)
			{
					$check = date("Y-F", strtotime($month.$year));
					if($check=='1970-January')
					return Response::view('404', array(), 404);
					
					$start = new Carbon('first day of'.$month.' '.$year);
					$end  = new Carbon('last day of'.$month.' '.$year);
					$date = date('d',strtotime($end));
					$month = date('F',strtotime($start));
					$year=$start->year;
			}
			else
			{
					$start = new Carbon('first day of this month');
					$end =  Carbon::now();
					$date = date('d');
					$month = date('F',strtotime($start));
					$year=$start->year;
			}
			$calmonth= $start->month;
			//******Previous Month*************//
			if($calmonth==1)
			{
				$premonth = 12;
				$preyear = $start->year-1;
			}
			else
			{
				$premonth = $calmonth-1;
				$preyear = $year;
			}
			$premonth = date("F", strtotime("2001-" . $premonth . "-01"));
			
			//******Next Month*************//
			if($calmonth==12)
			{
				$nextmonth = 01;
				$nextyear = $start->year+1;
			}
			else
			{
				$nextmonth = $calmonth+1;
				$nextyear = $start->year;
			}
			$nextmonth = date("F", strtotime("2001-" . $nextmonth . "-01"));
			
			$orders = Order::select(['order.created_at',DB::raw('sum(commission_amount) AS commission_amount'),DB::raw('sum(rowtotal_includetax) AS total_order_amount'),'order.order_id','order.grand_total',DB::raw("(DATE_FORMAT(`tbl_order`.created_at,$this->date)) as order_date")])
			->Join('order_item','order_item.order_id','=','order.order_id')
			->Join('order_payment','order_payment.order_id','=','order.order_id')
			->where('order_item.seller_id',$this->userId)
			->where(DB::raw("(`tbl_order`.created_at)"),'>=',$start)
			->where(DB::raw("(`tbl_order`.created_at)"),'<=',$end)
			->groupBy(DB::raw("(date(`tbl_order`.created_at))"))
			->orderBy('order.order_id', 'desc');
			
			$earining = DB::table(DB::raw("({$orders->toSql()}) as pro"))
			->mergeBindings($orders->getQuery()) 
			->select('order_date','created_at','commission_amount','total_order_amount')
			->groupby('order_id')
			->get();
		
        return view('vendor.earning.list',compact('nextyear','nextmonth','end','preyear','premonth','earining','date','month','year'));
    }
    
    public function transactionDetail($date)
    {
		
		if((string) (int) $date === $date)
		{
			$date = date('Y-m-d',$date);
			$transaction_detail = Order::select('order.order_id')
			->Join('order_item','order_item.order_id','=','order.order_id')
			->Join('order_payment','order_payment.order_id','=','order.order_id')
			->where('order_item.seller_id',$this->userId)
			->where(DB::raw("(date(`tbl_order`.created_at))"),'=',$date)
			->groupBy('order.order_id')
			->get();
			
			foreach($transaction_detail as $key=>$value)
			{
				$transaction_detail[$key]->orderitem = OrderItem::
				select('order_item.seller_id','order_item.order_id','order_item.product_description','order_item.price','order_item.rowtotal_includetax','order_item.tax_percentage','order_item.rowtotal_includetax','order_item.commission_amount','order_address.first_name',DB::raw("(DATE_FORMAT(`tbl_order_item`.created_at,$this->date)) as order_date"))
				->Join('order_address','order_address.order_id','=','order_item.order_id')
				->Join('order_payment','order_payment.order_id','=','order_item.order_id')
				->where('order_item.order_id',$value->order_id)
				->where('order_item.seller_id',$this->userId)
				->where(DB::raw("(date(`tbl_order_item`.created_at))"),'=',$date)
				->get();
			}
			 return view('vendor.earning.detail',compact('transaction_detail','date'));
		 }
		 else
		 {
			 	return Response::view('404', array(), 404);
		 }
		
	}
   
}
