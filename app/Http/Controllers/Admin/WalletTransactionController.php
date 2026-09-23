<?php
namespace App\Http\Controllers\Admin;
use App\Helpers\datehelper;
//use App\KHAgent;
use App\CustomerPoints;
use App\User;
use Datatables;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Illuminate\Http\Request;
use View;

class WalletTransactionController extends CodespurController {
	private $user_activation = true;
	function __construct() {
		$this->date = datehelper::dateformat();
	}
	public function index() {
		$PARENT_ID = 1;
		$data = 'ok';
		$users=User::select('id','full_name','mobileno')->orderBy('full_name','ASC')->get();
		return view('admin.wallet_transaction.list', compact('PARENT_ID', 'data','users'));
	}
	
	public function data(Request $request) {


		
		$from = $request->trasanction_from;
		$to = $request->trasanction_to;
		// echo $to;die;
		ini_set('memory_limit','-1');
		$data = CustomerPoints::select('customer_points.*','users.full_name', DB::raw("DATE_FORMAT(`tbl_customer_points`.created_at,'%d %M  %Y ') as add_date"));
		$temp = $data->leftJoin('users', 'users.id', 'customer_points.user_id');
		// echo "<pre>";print_r($temp);die;
		
		if (!empty($request->user)) {
			$temp = $temp->where('users.id', $request->user);
		}

		if (!empty($from)) {
			$temp = $temp->whereDate('customer_points.created_at', '>=', $from);
		}

		if (!empty($to)) {
			$temp = $temp->whereDate('customer_points.created_at', '<=', $to);
		}

		if (!empty($request->trasanction_type)) {
			$temp = $temp->where('customer_points.transaction_type', $request->trasanction_type);
		}

		if (!empty($request->coupon_no)) {
			$temp = $temp->where('customer_points.coupon_no', $request->coupon_no);
		}
		
		if (!empty($request->qr_value)) {
			$temp = $temp->where('customer_points.qr_value', $request->qr_value);
		}
		
		$temp = $temp->orderBy('customer_points.id', 'DESC');
		$data = $temp->get();
		return Datatables::of($data)->make(true);
	}
	public function exportExcel(Request $request)
	{
		$from = $request->trasanction_from;
		$to = $request->trasanction_to;
		$csp=CustomerPoints::select('customer_points.*','users.full_name','users.mobileno','products.product_group_code','products.density','products.length','products.width','products.thickness', DB::raw("DATE_FORMAT(`tbl_customer_points`.created_at,'%d/%m/%Y ') as add_date"));
		$temp = $csp->leftJoin('users', 'users.id', 'customer_points.user_id');
		$temp = $csp->leftJoin('products', 'products.id', 'customer_points.product_id');
		if (!empty($request->user)) {
			$temp = $temp->where('users.id', $request->user);
		}

		if (!empty($from)) {
			$temp = $temp->whereDate('customer_points.created_at', '>=', $from);
		}

		if (!empty($to)) {
			$temp = $temp->whereDate('customer_points.created_at', '<=', $to);
		}

		if (!empty($request->trasanction_type)) {
			$temp = $temp->where('customer_points.transaction_type', $request->trasanction_type);
		}

		if (!empty($request->coupon_no)) {
			$temp = $temp->where('customer_points.coupon_no', $request->coupon_no);
		}
		
		if (!empty($request->qr_value)) {
			$temp = $temp->where('customer_points.qr_value', $request->qr_value);
		}
		$temp = $temp->orderBy('customer_points.id', 'DESC');
		$csp = $temp->get();
		// echo '<pre>';print_r($csp);die;
		Excel::create('CustomerPoints'.date('d-m-Y'), function($excel) use($csp) {

			$excel->sheet('New sheet', function($sheet) use($csp) {
				$sheet->setWidth(array(
					'A'     =>  40,
					'B'     =>  20,
					'C'     =>  40,
					'D'     =>  40,
					'E'     =>  40,
					'F'     =>  40,
					'G'     =>  40,
					'H'     =>  40,
					'I'     =>  40,
					'J'     =>  40,
					'K'     =>  40,
					'L'     =>  40,
					'M'     =>  40,
					'N'     =>  40,
					
				));
				$sheet->loadView('csp', ['csp' => $csp]);
			})->download('xls');

		});
		// return Excel::download(new UsersExport, 'list.xlsx');
	}
}