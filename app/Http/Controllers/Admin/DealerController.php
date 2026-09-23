<?php
namespace App\Http\Controllers\Admin;
use Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use View;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use App\Dealer as app_modal;
use App\Dealer;
use App\SendMessage;
use App\States;
use App\User;
use App\District;
use App\Roles;
use App\RoleUser;
use Validator;
use DB;
use Datatables;
use Redirect;
use Crypt;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use App\Helpers\datehelper;
class DealerController extends Controller{
	function __construct()
	{
		$this->date=datehelper::dateformat();
		$this->manager_name  = 'Dealer';
		$this->folder_name   = 'dealer';
		$this->manager_url   = route('admin.dealer');
		$this->route_data      = route('admin.dealer.data');
		$this->route_create      = route('create.dealer');

	}
	//================== Admin   View Function ==================//
	public function index(){
		$manager_name  = $this->manager_name;
		$PARENT_ID     = 147;
		$route_data    = $this->route_data;
		$route_create    = $this->route_create;
		$folder_name    = $this->folder_name;
		return view('admin.'.$this->folder_name.'.list',compact('PARENT_ID','folder_name','manager_name','route_create','route_data'));
	}
	public function data()
	{
		$data =app_modal::select('dealers.*','dealers.id as dealer_id','users.full_name')->join('users','users.id','dealers.sales_officer_id')->get();
		return Datatables::of($data)
		->addColumn('actions', '<div class="btn-group">
			<a class="btn btn-primary" data-toggle="modal" data-target="#modal-large" href="{{URL::to("cpmin/dealer/edit/$dealer_id")}}" title="Edit"><i class="fa fa-edit"></i> </a>
			<a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="{{URL::to("cpmin/dealer/$dealer_id/confirm-delete")}}" title="Delete"><i class="fa fa-trash"></i> </a>
			</div>
			')
		->rawColumns(['actions'])
		->make(true);
	}
	public function create($id=null)
	{
		$manager_name  = $this->manager_name;
		$PARENT_ID     = 147;
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
		$salesofficer = User::select('full_name', 'mobileno','users.id','employee_id')
		->join('role_users', 'role_users.user_id', '=', 'users.id')
		->join('roles', 'roles.id', '=', 'role_users.role_id')
		->where('roles.slug', 'salesofficier')
		->get();
		return view('admin.'.$this->folder_name.'.create',compact('data','PARENT_ID','folder_name','manager_name','route_create','route_data','manager_url','states','district_data','salesofficer'));
	}
	public function getModalDelete($id = null)
	{
		$model = 'Dealer';
		$confirm_route = $error = null;
		$confirm_route = route('delete.dealer', ['id' => $id]);
		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
	}
	public function destroy($id)
	{
		$data = app_modal::where('id',$id)->delete();
		$success ="Dealer Deleted Successfully.";
		$route = route('admin.dealer');
		return Redirect($route)->with('success', $success);
	}
	public function store(Request $request,$id=null)
	{
		// dd($request->all());
		$input=Input::all();

		if ($id != null) {
			$rules['dealer_name'] ='required';
			$rules['sales_officer_id'] ='required';
			$rules['mobile_no'] ='required|numeric|digits:10';
			$rules['dealership_location'] ='required';
			$rules['address'] ='required';
			$rules['state_id'] ='required';
			$rules['city'] ='required';
			$rules['district_id'] ='required';
			$rules['pincode'] ='required';
			$rules['contact_person_name'] ='required';
			$rules['contact_person_designation'] ='required';
			$rules['contact_person_mobile_no'] ='required|numeric|digits:10';
			$rules['other_details'] ='required';
		}
		else{
			$rules['dealer_name'] ='required';
			$rules['sales_officer_id'] ='required';
			$rules['mobile_no'] ='required|numeric|digits:10|unique:users,mobileno'.$id;
			$rules['email'] ='required|email|unique:users,email'.$id;
			$rules['dealership_location'] ='required';
			$rules['address'] ='required';
			$rules['state_id'] ='required';
			$rules['city'] ='required';
			$rules['district_id'] ='required';
			$rules['pincode'] ='required';
			$rules['contact_person_name'] ='required';
			$rules['contact_person_designation'] ='required';
			$rules['contact_person_mobile_no'] ='required|numeric|digits:10';
			$rules['other_details'] ='required';
		}
		
		
		$errorMsg="Opps ! Some Error Occured. Please Try Again.";
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
		}
		$records= app_modal::Select('id')->where('id',$id)->first();
		if($id == null){
			$data = new Dealer;
			$credentials = [
                'email'    => $request->email,
                'password' => Crypt::encrypt("lar_123456"),
            ];
            
			$userSentinal = Sentinel::create($credentials);
			$user = $userSentinal;
			$activation2 = Activation::create($userSentinal);
			Activation::complete($user, $activation2->code);
			$userSentinal->email = $request->email;
			$userSentinal->save();
            $role = Roles::where('slug','dealer')->first();         
            $roleUser=new RoleUser();
            $roleUser->user_id=$userSentinal->id;
            $roleUser->role_id=$role->id;
            $roleUser->save();
			$data->user_id = $userSentinal->id;
			$data->email 							= $request->email;
		}
		else{
			$data = Dealer::where('id',$id)->first();
		}
			$data->dealer_name 						= $request->dealer_name;
			$data->sales_officer_id 				= $request->sales_officer_id;
			$data->mobile_no 						= $request->mobile_no;
			$data->dealership_location 				= $request->dealership_location;
			$data->address 							= $request->address;
			$data->state_id 						= $request->state_id;
			$data->city 							= $request->city;
			$data->district_id 						= $request->district_id;
			$data->pincode 							= $request->pincode;
			$data->contact_person_name 				= $request->contact_person_name;
			$data->contact_person_designation 		= $request->contact_person_designation;
			$data->contact_person_mobile_no 		= $request->contact_person_mobile_no;
			$data->other_details 					= $request->other_details;
			$data->save();
		
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
		$output['url'] = route('admin.dealer');
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
}
