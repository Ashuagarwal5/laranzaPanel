<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Logs;
use App\CustomerRewards;
use App\CustomerPoints;
use App\User;
use Redirect;
use Sentinel;
use Session;
use View;
use DB;
use Datatables;
use Input;
use Response;
use Requests;
use Validator;
use Storage;
use App\Helpers\datehelper;
use Maatwebsite\Excel\Facades\Excel;


class CustomerPointsController extends CodespurController
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
    public function customer_point_history(Request $request, $userid = null)
    {
		//echo "done"; die;
    	$PARENT_ID=1;
		
		  //   	$history = CustomerPoints::select('users.full_name','users.id',
  //   		DB::raw(" (SELECT SUM(tbl_customer_points.point) FROM tbl_customer_points WHERE(tbl_customer_points.user_id = tbl_users.id AND tbl_customer_points.transaction_type = 'Earn')) as total_earned"),
  //   		DB::raw(" (SELECT SUM(tbl_customer_points.point) FROM tbl_customer_points WHERE(tbl_customer_points.user_id = tbl_users.id AND tbl_customer_points.transaction_type = 'Redeem')) as total_redeemed")
  //   	)
  //   	->join('users','users.id','customer_points.user_id')
  //   	->groupBy('customer_points.user_id')
  //   	->get();

		// echo "<pre>"; print_r($history); die;
		
	    $history = CustomerPoints::select(
	    'users.full_name','users.id',
	    'users.mobileno','users.city',
	    'users.state',
    		DB::raw(" (SELECT SUM(tbl_customer_points.point) FROM tbl_customer_points WHERE(tbl_customer_points.user_id = tbl_users.id AND tbl_customer_points.transaction_type = 'Earn')) as total_earned"),
    		DB::raw(" (SELECT SUM(tbl_customer_points.point) FROM tbl_customer_points WHERE(tbl_customer_points.user_id = tbl_users.id AND tbl_customer_points.transaction_type = 'Redeem')) as total_redeemed")
    	)
    	->join('users','users.id','customer_points.user_id')
    	->groupBy('customer_points.user_id');
		
		if(isset($userid)){
	    	$history = $history->where('users.id',$userid);
    	}

    	$history = $history->paginate(10);
    	

    	//return view('admin.customer-points.index',compact('PARENT_ID','history'));
 		$users=User::select('id','full_name','mobileno')->orderBy('full_name','ASC')->get();   	
    	return view('admin.customer-points.customer-point-history',compact('PARENT_ID','history','users','userid'));
    }
    public function customer_point_history_data()
    {

    	$history = CustomerPoints::select('users.full_name','users.id',
    		DB::raw(" (SELECT SUM(tbl_customer_points.point) FROM tbl_customer_points WHERE(tbl_customer_points.user_id = tbl_users.id AND tbl_customer_points.transaction_type = 'Earn')) as total_earned"),
    		DB::raw(" (SELECT SUM(tbl_customer_points.point) FROM tbl_customer_points WHERE(tbl_customer_points.user_id = tbl_users.id AND tbl_customer_points.transaction_type = 'Redeem')) as total_redeemed")
    	)
    	->join('users','users.id','customer_points.user_id')
    	->groupBy('customer_points.user_id')
    	->get();
    	// echo "<pre>";print_r($history);die;
    	return Datatables::of($history)
    	->make(true);
    }






 	public function exportExcel(Request $request)
	{
       	$data = CustomerPoints::select(
			    'users.full_name','users.id',
			    'users.mobileno','users.city',
			    'users.state',
		    		DB::raw(" (SELECT SUM(tbl_customer_points.point) FROM tbl_customer_points WHERE(tbl_customer_points.user_id = tbl_users.id AND tbl_customer_points.transaction_type = 'Earn')) as total_earned"),
		    		DB::raw(" (SELECT SUM(tbl_customer_points.point) FROM tbl_customer_points WHERE(tbl_customer_points.user_id = tbl_users.id AND tbl_customer_points.transaction_type = 'Redeem')) as total_redeemed")
		    	)
	    	->join('users','users.id','customer_points.user_id')
	    	->groupBy('customer_points.user_id')
	    	->paginate(2500);

        
		// echo "<pre>";print_r($users);die;
		Excel::create('Customer_Transaction_Summary-'.date('d-m-Y'), function($excel) use($data) {
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
				$sheet->loadView('admin.customer-points.transaction-summary-excel', ['data' => $data]);
			})->download('xls');
		});
		// return Excel::download(new UsersExport, 'list.xlsx');
	}




    public function reward_redemption_history()
    {

    	$PARENT_ID=68;

    	return view('admin.customer-points.reward_redemption_history',compact('PARENT_ID'));
    }

    public function reward_redemption_history_data()
    {


    	$data = CustomerPoints::select('users.full_name','customer_rewards.reward','customer_rewards.image','customer_rewards.qualification_points',
    		'customer_points.id',DB::raw("DATE_FORMAT(tbl_customer_points.created_at,'%d %M  %Y') as add_date")
    	)
    	->join('customer_rewards','customer_rewards.id','customer_points.reward_id')
    	->join('users','users.id','customer_points.user_id')
    	->where('customer_points.transaction_type','Redeem')
    	->groupBy('customer_points.id')
    	->get();


    	return Datatables::of($data)
		         //~ ->addColumn('actions', '
              		//~ ')
       //~ ->rawColumns(['actions'])
    	->make(true);




			//echo  "<pre>"; print_r($history); die;



    }


















    /****************************************************************	 customer_rewards  ************************************/

    public function customer_rewards()
    {
		//echo "done"; die;



    	$query = CustomerRewards::select('customer_rewards.*');
    	$query->addSelect(DB::raw("(SELECT COUNT(tbl_customer_points.reward_id) FROM tbl_customer_points WHERE (tbl_customer_points.reward_id = tbl_customer_rewards.id)) as total_red"));

    	$data = $query->orderBy('id','DESC')->get();

		//echo  "<pre>"; print_r($data); die;
		        // get();
    	$PARENT_ID=68;
    	return view('admin.customer-points.customer_rewards',compact('PARENT_ID','data'));
    }

    public function customer_rewards_add($ID=NULL)
    {
    	$PARENT_ID=68;
    	if($ID)
    	{
    		$data=CustomerRewards::find($ID);
    		if(count($data)==0)
    		{
    			$messgae="This is not valid action, data not found.";
    			return redirect('admin.customer.rewards')->with('error', trans($messgae));
    		}


    		return view('admin.customer-points.customer_rewards_add',compact('PARENT_ID','data'));

    	}


		//echo "done"; die;

    	return view('admin.customer-points.customer_rewards_add',compact('PARENT_ID'));
    }

    public function customer_rewards_add_post(Request $request, $id=null){


    	if($id!=null)
    	{
    		if($request->product_image)
    			$rules['image']			= "mimes:jpg,jpeg,png";
    	}
    	else
    	{
    		$rules['image']			= "required|mimes:jpg,jpeg,png";
    	}

    	$input=$request->all();
    	$rules['qualification_points'] 		= "required|numeric";
    	$rules['reward'] 		= "required";
    	$rules['description'] 		= "required";
			//$rules['image'] 		= "required|mimes:jpg,jpeg,png";


    	$msg							= "Please fill required fields.";
    	$msgHead						= "Error !";
    	$msgType						= "error";
    	$validator = Validator::make($request->all(), $rules);
    	if ($validator->fails()) {
    		return response()->json(['errorArray'=>$validator->errors(),'msg'=>$msg,'msgHead'=>$msgHead,'msgType'=>$msgType,'slideToTop'=>'yes']);
    	}
    	else{


    		$file = $request->file('image');


    		if (!empty($file))
    		{
    			$extension = $file->extension();
    			$folderName = '/customer-reward';
    			$safeName = str_random(10) . '.' . $extension;
    			@mkdir($folderName,0777,true);
    			@chmod($folderName,0777);
    			Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
    			$input_save['image'] = $safeName;

    		}


    		$input_save['qualification_points']    = $request->get('qualification_points');
    		$input_save['reward'] = $request->get('reward');
    		$input_save['description'] = $request->get('description');

    		$data = CustomerRewards::updateOrCreate(['id' => $id],$input_save);



    		$output['PARENT_ID']		= 3;
    		$output['msg']				= "Point Settings Settings Updated Successfully.";
    		$output['msgHead']			= "Success ! ";
    		$output['msgType']			= "success";
    		$output['status']			= 'success';
    		$output['resetform']	    = true;
    		$output['slideToTop']		= true;
    		$output['url']		        = route('admin.customer.rewards');

    		return json_encode($output);
    	}
    }

    public function getModalDelete($id)
    {
    	$confirm_route = $error = null;

    	$model='Reward';
    	$confirm_route = route('final-delete/admin_manager', ['id' => $id]);



    	return View('admin/layouts/delete_modal_confirmation', compact('confirm_route','model','error'));
    }
    public function deleteRecord($id){
		// echo "delete"; die;
    	try {


    		$result=DB::table('customer_rewards')->where('id',$id)->delete();



    		$notification = array(
    			'message' => 'Record deleted successfully.',
    			'alert-type' => 'success'
    		);

    		return Redirect::to('cpmin/customer-rewards')->with($notification);


    	} catch (ModelNotFoundException $e) {
    		return Response::view('404', array(), 404);
    	}
    }


}
