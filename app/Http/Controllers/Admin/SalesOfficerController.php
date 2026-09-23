<?php
namespace App\Http\Controllers\Admin;
use Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use View;
use App\User as app_modal;
use App\Dealer;
use App\SendMessage;
use App\States;
use App\User;
use App\District;
use App\Roles;
use App\RoleUser;
use Validator;
use Crypt;
use DB;
use Datatables;
use Redirect;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use App\Helpers\datehelper;
class SalesOfficerController extends Controller{
	function __construct()
	{
		$this->date=datehelper::dateformat();
		$this->manager_name  = 'Sales Officer';
		$this->folder_name   = 'sales_officer';
		$this->manager_url   = route('admin.sales_officer');
		$this->route_data      = route('admin.sales_officer.data');
		$this->route_create      = route('create.sales_officer');

	}
	//================== Admin   View Function ==================//
	public function index(){
		$manager_name  = $this->manager_name;
		$PARENT_ID     = 150;
		$route_data    = $this->route_data;
		$route_create    = $this->route_create;
		$folder_name    = $this->folder_name;
		return view('admin.'.$this->folder_name.'.list',compact('PARENT_ID','folder_name','manager_name','route_create','route_data'));
	}
	public function data()
	{
		$data =app_modal::select('users.*')
		->join('role_users', 'role_users.user_id', '=', 'users.id')
		->join('roles', 'roles.id', '=', 'role_users.role_id')
		->where('roles.slug', 'salesofficier')
		->get();
		return Datatables::of($data)
		->addColumn('actions', '<div class="btn-group">
			<a class="btn btn-primary" data-toggle="modal" data-target="#modal-large" href="{{URL::to("cpmin/sales_officer/edit/$id")}}" title="Edit"><i class="fa fa-edit"></i> </a>
			<a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="{{URL::to("cpmin/sales_officer/$id/confirm-delete")}}" title="Delete"><i class="fa fa-trash"></i> </a>

			<a class="btn btn-success" data-toggle="modal" data-target="#modal-large" href="{{URL::to("cpmin/sales_officer/all-dealers/$id")}}" title="All Dealers"><i class="fa fa-file"></i> </a>
			</div>
			')
		->rawColumns(['actions'])
		->make(true);
	}
	public function create($id=null)
	{
		$manager_name  = $this->manager_name;
		$PARENT_ID     = 150;
		$route_data    = $this->route_data;
		$route_create    = $this->route_create;
		$manager_url=$this->manager_url;
		$folder_name    = 'Change Status';
		$states = States::select('name','id')->get();
		$data=app_modal::select('*')->where('id',$id)->first();
		$district_data = null;
		if ($data) {
			$district_data = District::where('state_id',$data->state_id)->get();
		}
		$salesofficer = User::select('full_name', 'mobileno','users.id')
		->join('role_users', 'role_users.user_id', '=', 'users.id')
		->join('roles', 'roles.id', '=', 'role_users.role_id')
		->where('roles.slug', 'salesofficer')
		->get();
		return view('admin.'.$this->folder_name.'.create',compact('data','PARENT_ID','folder_name','manager_name','route_create','route_data','manager_url','states','district_data','salesofficer'));
	}
	public function getModalDelete($id = null)
	{
		$model = 'Sales Officer';
		$confirm_route = $error = null;
		$confirm_route = route('delete.sales_officer', ['id' => $id]);
		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
	}
	public function destroy($id)
	{
		$data = app_modal::where('id',$id)->delete();
		$success ="Sales Officer Deleted Successfully.";
		$route = route('admin.sales_officer');
		return Redirect($route)->with('success', $success);
	}
	public function store(Request $request,$id=null)
	{
		$input=Input::all();
		if ($id != null) {
			$rules['full_name'] ='required';
			$rules['employee_id'] ='required';
			$rules['mobileno'] ='required|numeric|digits:10';
			$rules['email'] ='required|email';
			
			$rules['state'] ='required';
			$rules['city'] ='required';
			$rules['pincode'] ='required|numeric|digits:6';

		}
		else{
			$rules['full_name'] ='required';
			$rules['employee_id'] ='required';
			$rules['mobileno'] ='required|numeric|digits:10|unique:users,mobileno';
			$rules['email'] ='required|email|unique:users,email';
			$rules['password'] ='required';
			
			$rules['state'] ='required';
			$rules['city'] ='required';
			$rules['pincode'] ='required|numeric|digits:6';

		}
		
		$errorMsg="Opps ! Some Error Occured. Please Try Again.";
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
		}
		$records= app_modal::Select('id')->where('id',$id)->first();   
		if($id == null){
			$credentials = [
				'email'    => $request->email,
				'password' => $request->password
			];
			$userSentinal = Sentinel::create($credentials);
			$user = $userSentinal;
			$activation2 = Activation::create($userSentinal);
			Activation::complete($user, $activation2->code);
			$userSentinal->full_name = $request->full_name;
			$userSentinal->email = $request->email;
			$userSentinal->mobileno = $request->mobileno;
			$userSentinal->employee_id = $request->employee_id;
			$userSentinal->state = $request->state;
			$userSentinal->city = $request->city;
			$userSentinal->pincode = $request->pincode;
			$userSentinal->save();
	
			$role = Roles::where('slug','salesofficier')->first();
		   
			$roleUser=new RoleUser();
			$roleUser->user_id=$userSentinal->id;
			$roleUser->role_id=$role->id;
			$roleUser->save();
		}
		else{
			$data = User::where('id',$id)->first();
			$data->email = $request->email;
			$data->full_name = $request->full_name;
			$data->mobileno = $request->mobileno;
			$data->employee_id = $request->employee_id;
			$data->state = $request->state;
			$data->city = $request->city;
			$data->pincode = $request->pincode;
			$data->save();
		}
		
		
		if($id!=NULL)
			$messgae= $this->manager_name." Updated Successfully";
		else
			$messgae= $this->manager_name." Created Successfully";

		$output['status'] = 'success';
		$output['success_msg'] = $messgae;
		$output['msg'] = $messgae;
		$output['msgHead'] = "Success ! ";
		$output['msgType'] = "success";
		$output['success'] = true;
		$output['slideToTop'] = true;
		$output['url'] = route('admin.sales_officer');
		return response()->json($output);
	}
	public function validateotp(Request $request){
		$rules['message_otp']			= "required|numeric";
    	$errorMsg						= "Opps ! Please fill required fields.";
    	$validator = Validator::make($request->all(), $rules);
    	if ($validator->fails()) {
    		return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
    	}
    	else
    	{
			$otp = $request->message_otp;
			$validate = SendMessage::getSendMessage($type = null, $otp);
			return $validate;
		}
	}
	public function get_district(Request $request){
		$data = District::where('state_id', $request->state_id)->where('deleted_at', null)->get();
		return response()->json($data);
	}
	public function all_dealers(Request $request, $id){
		$data = Dealer::where('sales_officer_id',$id)->get();
		return view('admin.'.$this->folder_name.'.all_dealers',compact('data'));
	}
}
