<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Products;
use App\Helpers\Thumbnail;
use App\Category;
use App\ProductsBranches;
use App\BranchStores;
use App\ProductImages;
use App\ProductAttributes;
use App\SellerDetails;
use App\SendMessage;
use App\PointsRedeem;
use App\CustomerPoints;
use App\Notification;
use App\Bank_detail;
use App\WebsiteSetting;
use App\User;
use Redirect;
use Sentinel;
use Session;
use View;
use DB;
use Datatables;
use Illuminate\Support\Facades\Input;
use Response;
use Validator;
use App\Helpers\AjaxFormValidator;
use File;
use Storage;
use App\Helpers\datehelper;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use App\Import\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;

class RedeemController extends CodespurController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    function __construct()
    {
    	$this->date=datehelper::dateformat();
        $this->manager='Redemption';
    }
    public function index()
    {

    	$PARENT_ID=124;
        $manager_name='Pending '.$this->manager;
        return view('admin.redeem.list',compact('PARENT_ID','manager_name'));
    }
            //=============== All List Data Function ==========================//
    public function data(Request $request){

        $data = CustomerPoints::select('customer_points.*',
        'users.full_name as user_name',
        'users.mobileno as user_mobile',
        'users.user_type',DB::raw("(DATE_FORMAT(`tbl_customer_points`.created_at,'%d %M %y')) as add_date"));
        $data=$data->join('users','users.id','customer_points.user_id');
        $temp =  $data->where('customer_points.reward_status','pending')->where('customer_points.transaction_type','Redeem');
        $data = $temp->get();

        return Datatables::of($data)
        ->addColumn('actions', '
            <a  class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal-email" title="Processing '.$this->manager.'" href="{{route("admin.redeem.processed.modal",["$id"])}}">
            <i class="fa fa-circle-o"></i>
            </a>
            <a  class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-email" title="Reject '.$this->manager.'" href="{{route("admin.redeem.rejected",["$id","dotask"])}}">
            <i class="fa fa-ban"></i>
            </a>
            ')
        ->rawColumns(['actions'])
        ->make(true);
    }
    
 	public function exportExcel(Request $request)
	{
		$data = PointsRedeem::select('points_redeem.id','points_redeem.points',
        'users.full_name as user_name',
        'users.mobileno as user_mobile',
        'users.city as user_city',
        'users.state as user_state',
        'users.pincode as user_pincode',
        'users.user_type',DB::raw("(DATE_FORMAT(`tbl_points_redeem`.created_at,'%d %M %y')) as add_date"));
        $data=$data->join('users','users.id','points_redeem.user_id');
        $temp =  $data->where('points_redeem.status','Pending');
        $temp =  $data->orderBy('points_redeem.id','desc');
        $data = $temp->get();
        
		// echo "<pre>";print_r($users);die;
		Excel::create('Redeem_Req_data-'.date('d-m-Y'), function($excel) use($data) {
			$excel->sheet('New sheet', function($sheet) use($data) {
				$sheet->setWidth(array(
					'A'     =>  10,
					'B'     =>  40,
					'C'     =>  20,
					'D'     =>  20,
					'E'     =>  20,
					'F'     =>  20,															
					'G'     =>  20,
					'H'     =>  20,
				));
				$sheet->loadView('admin.redeem.point_redeem_req', ['data' => $data]);
			})->download('xls');
		});
		// return Excel::download(new UsersExport, 'list.xlsx');
	}
   
    
    public function approved_list()
    {

        $PARENT_ID=124;
        $manager_name='Approved '.$this->manager;
        return view('admin.redeem.approved_list',compact('PARENT_ID','manager_name'));
    }
    
            //=============== All List Data Function ==========================//
    public function approved_list_data(Request $request){

        $data = CustomerPoints::select('customer_points.*','users.full_name as user_name','users.user_type',DB::raw("(DATE_FORMAT(`tbl_customer_points`.created_at,'%d %M %y')) as add_date"));
        $data=$data->join('users','users.id','customer_points.user_id');
        $temp =  $data->where('customer_points.reward_status','approved');
        $data = $temp->get();

        return Datatables::of($data)
        ->addColumn('actions', '
            <a  class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-email" title="View '.$this->manager.'" href="{{route("admin.redeem.approved_view",["$id"])}}">
            <i class="fa fa-eye"></i>
            </a>
            ')
        ->rawColumns(['actions'])
        ->make(true);
    }

    public function processing_list()
    {

        $PARENT_ID=124;
        $manager_name='Processing '.$this->manager;
        return view('admin.redeem.processing_list',compact('PARENT_ID','manager_name'));
    }
    public function processing_list_data(Request $request){

        $data = CustomerPoints::select('customer_points.*','users.full_name as user_name','users.user_type',DB::raw("(DATE_FORMAT(`tbl_customer_points`.created_at,'%d %M %y')) as add_date"));
        $data=$data->join('users','users.id','customer_points.user_id');
        $temp =  $data->where('customer_points.reward_status','processing');
        $data = $temp->get();

        return Datatables::of($data)
        ->addColumn('actions', '
            <a  class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal-email" title="Approved '.$this->manager.'" href="{{route("admin.redeem.approved.modal",["$id"])}}">
            <i class="fa fa-circle-o"></i>
            </a>
            <a  class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-email" title="Reject '.$this->manager.'" href="{{route("admin.redeem.rejected",["$id","dotask"])}}">
            <i class="fa fa-ban"></i>
            </a>
            ')
        ->rawColumns(['actions'])
        ->make(true);
    }
    public function rejected_list()
    {

        $PARENT_ID=124;
        $manager_name='Rejected '.$this->manager;
        return view('admin.redeem.rejected_list',compact('PARENT_ID','manager_name'));
    }
            //=============== All List Data Function ==========================//
    public function rejected_list_data(Request $request){

        $data = CustomerPoints::select('customer_points.*','users.full_name as user_name','users.user_type',DB::raw("(DATE_FORMAT(`tbl_customer_points`.created_at,'%d %M %y')) as add_date"));
        $data=$data->join('users','users.id','customer_points.user_id');
        $temp =  $data->where('customer_points.reward_status','cancelled');
        $data = $temp->get();

        return Datatables::of($data)
        ->make(true);
    }
    public function approved_modal(Request $request,$id){
        $detail = CustomerPoints::where('id', $id)->first();
        $point=$detail->point;
        $status = 'Approved';
        $error = false;
        if($detail == null)
            $error = 'Data Not Found';
        else
        {
            $confirm_route = route('admin.redeem.approved', [$id]);
            $model = $status.' '.$this->manager;
            $type = $status;

            $data = CustomerPoints::select('users_details.*');
            $data=$data->leftjoin('users','users.id','customer_points.user_id');
            $data=$data->leftjoin('users_details','users.id','users_details.user_id');
            $temp =  $data->where('users_details.status','Active');
            $temp =  $data->where('customer_points.id',$id);
            $temp =  $data->orderBy('users_details.id','DESC');
            $data = $temp->first();
            return view('admin/layouts/redeem_status_modal_confirmation', compact('error', 'confirm_route', 'model', 'type','data'));
        }
    }
    public function approved_view(Request $request,$id){
            // $confirm_route = route('admin.redeem.approved', [$id]);
            $model = "View".' '.$this->manager;
            $type = "View";
            $data = CustomerPoints::where('id', $id)->first();
            return view('admin/layouts/redeem_modal_view', compact('model', 'type','data'));
    }

    // public function payment_screenshot_view(){
    //     $data = CustomerPoints::get();
    //     return view('admin.redeem.view_payment_screenshot',compact('data'));
    // }

    public function processed_modal(Request $request,$id){
        $detail = CustomerPoints::where('id', $id)->first();
        $point=$detail->point;
        $status = 'Processing';
        $error = false;
        if($detail == null)
            $error = 'Data Not Found';
        else
        {
            $confirm_route = route('admin.redeem.processed', [$id]);
            $model = $status.' '.$this->manager;
            $type = $status;
            $data = Bank_detail::where('id',$id)->where('status','active')->first();
            return view('admin/layouts/status_modal_confirmation', compact('error', 'confirm_route', 'model', 'type','data'));
        }
    }
    public function processed($id=null, $task=null)
    {
                $details = CustomerPoints::where('id', $id)->first();
                $details->reward_status = 'processing';
                $details->save();
                $type = "Redemption Processing";
                $id = $details->user_id;
                $point = $details->point;
		        $output = SendMessage::getSendMessage($type,$id,$point);


                // $user         = User::select('id','full_name')->where('id',$details->user_id)->first();
                // $point=$details->point;
                // $notification ="Congratulations,\n Your redemption request has been approved please call on helpline number for more details.";

                // $notification_table = new Notification;
                // $notification_table->user_id = $user->id;
                // $notification_table->title = 'Redemption Request';
                // $notification_table->description = $notification;
                // $notification_table->type = Sentinel::findById($user->id)->roles[0]['slug'];
                // $notification_table->status = 'Pending';
                // $notification_table->image = 'Redeem.png';
                // $notification_table->save();
                return redirect()->route('admin.redeem')->with('success', 'Status Changed Successfully.');
           
    }
    
    public function approved(Request $request, $id=null, $task=null)
    {    
        $rules =[
            'payment_screenshot' => 'required|image|mimes:jpg,png,jpeg',
        ];
        
        $validator=Validator::make($request->all(), $rules);
        $errorMsg = "Oops ! Some Error Occured. Please Try Again.";
        if ($validator->fails()) {
            return ['status'=>'error','errorArray' => $validator->errors(), 'error_msg' => $errorMsg,'slideToTop'=>true];
        } 
        if($request->id!=null)
        {
            $details=new Self();
            $given_data = $request->given_data;
            $details = CustomerPoints::where('id', $id)->first();
            $details->reward_status = 'approved';
            $details->payment_data = $given_data;
            
            if($file = $request->file('payment_screenshot'))
			{
				$fileName = $file->getClientOriginalName();
				$extension = $file->getClientOriginalExtension();
				$folderName = '/PaymentScreenshot';
				$safeName = time(). '.' . $extension;
				@mkdir($folderName);
				@chmod($folderName,0777);			
				Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
				$details->payment_screenshot = $safeName;
			}
            $details->save();
            $res['resetform']=true;
        }
        $type = "Redemption Approval";
        $id = $details->user_id;
        $point = $details->point;
        $output = SendMessage::getSendMessage($type,$id,$point);
        $user  = User::select('id','full_name')->where('id',$details->user_id)->first();
        $point=$details->point;
        $notification ="Congratulations,\n Your redemption request has been approved please call on helpline number for more details.";

		$output['status']		= 'success';
		$output['success_msg']	= $notification ;
		$output['msgHead']		= "Success !";
		$output['msgType']		= "success";
		$output['success']		= true;
		$output['slideToTop']	= true;
		$output['url']			= route('admin.redeem.processing.list');
		
		return response()->json($output);


                // $rules = [
                //     'payment_screenshot' => 'required|image|mimes:jpg,png,jpeg',
                // ];
                // $validator=Validator::make($request->all(),$rules);


                // $rules =[
                //     'payment_screenshot' => 'required|image|mimes:jpg,png,jpeg',
                // ];
                
                // $validator=Validator::make($request->all(), $rules);
                // $errorMsg = "Oops ! Some Error Occured. Please Try Again.";
                // if ($validator->fails()) {
                //     return ['status'=>'error','errorArray' => $validator->errors(), 'error_msg' => $errorMsg,'slideToTop'=>true];
                // } 
                // if ($file = $request->file('payment_screenshot'))
				// {
				// 	$fileName = $file->getClientOriginalName();
				// 	$extension = $file->getClientOriginalExtension();
				// 	$folderName = '/PaymentScreenshot';
				// 	$safeName = time(). '.' . $extension;
				// 	@mkdir($folderName);
				// 	@chmod($folderName,0777);			
				// 	Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
				// 	$details->payment_screenshot = $safeName;
				// }
                
                // $res['status']='success';
                // $res['slideToTop']=true;
                // return $res;

               

                // $notification_table = new Notification;
                // $notification_table->user_id = $user->id;
                // $notification_table->title = 'Redemption Request';
                // $notification_table->description = $notification;
                // $notification_table->type = Sentinel::findById($user->id)->roles[0]['slug'];
                // $notification_table->status = 'Pending';
                // $notification_table->image = 'Redeem.png';
                // $notification_table->save();



                // DB::table('notifications')->insert([
                //     'user_id'     => $user->id,
                //     'title'       => ,
                //     'description' => 
                //     'type'        => Sentinel::findById($user->id)->roles[0]['slug'],
                //     'status'      => 'Pending',
                //     'image'       => 'Redeem.png',
                //     'created_at'      => date('Y-m-d H:i:s'),
                //     'updated_at'      => date('Y-m-d H:i:s')
                // ]);

                // return redirect()->route('admin.redeem.processing.list')->with('success', 'Status Changed Successfully.');
           
    }
    public function rejected($id=null, $task=null)
    {

        $detail = CustomerPoints::where('id', $id)->first();
        $status = 'cancelled';


        $error = false;

        if($detail == null)
            $error = 'Data Not Found';

        if($task == 'dotask')
        {
            $confirm_route = route('admin.redeem.rejected', [$id, 'confirmed']);
            $model = $status.' '.$this->manager;
            $type = $status;
            return view('admin/layouts/status_modal_confirmation', compact('error', 'confirm_route', 'model', 'type'));
        }
        
        if($task == 'confirmed')
        {
            if($detail != null)
            {
             	$user  = User::select('id','full_name')->where('id',$detail->user_id)->first();
			 	$detail->reward_status = 'cancelled';
			 	$detail->save();
			 	
			 	$type = "Redemption Cancelled";
                $id = $detail->user_id;
                $point = $detail->point;
		        $output = SendMessage::getSendMessage($type,$id,$point);
				//  $detail->update(['reward_status' => $status]);
				$notification ="Sorry,\n your redemption request has been declined please call on helpline number for more details.";
				//  DB::table('notifications')->insert([
				//     'user_id'     => $user->id,
				//     'title'       => 'Redemption Request',
				//     'description' => $notification,
				//     'type'        => Sentinel::findById($user->id)->roles[0]['slug'],
				//     'status'      => 'Pending',
				//     'created_at'      => date('Y-m-d H:i:s'),
				//     'updated_at'      => date('Y-m-d H:i:s')
				// ]);
				
				
				
				//=>=>=>=>=>=>Since Redemption Request is cancelled so Return Points back which was deducted during redemption==#
				
				$allowPointRefund = WebsiteSetting::where('id', 1)->first()->refund_points_redeem_cancelled;
				
				if(isset($allowPointRefund) && $allowPointRefund == 'Yes')
				{
					$pointBalance = CustomerPoints::getUserBalance($detail->user_id);
					$currentPoints = $pointBalance['balance']+$detail->point;
					
					DB::table('customer_points')->insert(
		            array(
		                'point'            => $detail->point,
		                'qr_value'         => null,
		                'product_id'       => null,
		                'user_id'          => $detail->user_id,
		                'transaction_type' => 'Earn',
		                'current_points'   => $currentPoints,
		                'added_from'	   => 'admin',
		                'description'	   => 'Redeem Request Cancelled',
		                'redeem_req_id'	   => $detail->id
						)
					);
				}
				//=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>#
				
				
             return redirect()->route('admin.redeem')->with('success', 'Status Changed Successfully.');
         }
			else
			{
            return redirect()->route('admin.redeem')->with('error', 'Data not found .');
        }
    }
    return redirect()->back();
}
}