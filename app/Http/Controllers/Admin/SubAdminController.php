<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Customers;
use App\User;
use App\SupportOfficers;
use App\KHAgent;
use Redirect;
use Sentinel;
use Session;
use View;
use App\Country;
use App\States;
use App\City;
use App\RoleUser;
use App\Roles;
use App\Activations;
use DB;
use File;
use Datatables;
use Response;
use Validator;
use App\Helpers\datehelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Crypt;
class SubAdminController extends CodespurController
{
	private $user_activation = true;
	function __construct()
	{
		$this->date=datehelper::dateformat();
		$this->manager_name  = 'Sub Admin';
		$this->manager_url   = route('admin.sub_admin');
	} 
	public function index()
	{	
		// die('vv');
		$PARENT_ID=128;
			// $totalRecord = TripManagers::count();
		return view('admin.sub_admin.list',compact('PARENT_ID'));
	}
	//=============== All List Data Function ==========================//
	public function data(){
		//echo "done"; die;
		$data = User::select('users.id as userid','users.full_name as name','users.mobileno as mobile_no','users.email as email_address',DB::raw("(DATE_FORMAT(`tbl_users`.created_at,'%d/%m/%Y')) as add_date"))
		->join('role_users', 'role_users.user_id', 'users.id')
		->where('role_users.role_id',3)
		// ->where('users.user_type','supportofficer')
		->orderBy('id','desc')
		->get();
		return Datatables::of($data)
		->addColumn('actions', '<a title="View Info" class="btn btn-success btn-xs purple" href="{{URL::to("admin/sub_admin/show/$userid")}}"data-toggle="modal" data-target="#modal-email">
			<i class="fa fa-eye"></i>
			</a>
			<a class="delval btn-xs btn btn-primary" href="{{URL::to("admin/sub_admin/edit/$userid")}}" title="Edit"><i class="fa fa-edit"></i> </a>
			<a data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/sub_admin/$userid/confirm-delete")}}" class="delval btn btn-xs btn-danger"  title="Delete ">
			<i class="fa fa-trash"></i>
			</a>
			<a title="Password Change" class="btn btn-success btn-xs purple" href="{{URL::to("admin/sub_admin/change-password/$userid")}}"><i class="fa fa-key"></i>
			</a>
			')
		->rawColumns(['actions'])
		->make(true);
	}
	
	public function create($ID=null){
		$PARENT_ID=128;
		$manager_name = $this->manager_name;
		if($ID)
		{
			$route=route('admin.sub_admin.edit.store', ['id' => $ID]);
			$data=User::find($ID);
			if(count($data)==0)
			{
				$messgae="This is not valid action, data not found.";
				return redirect($this->manager_url)->with('error', trans($messgae));
			}
			$companyArray = array();
			if($ID != '')
			{
				$record = User::select('users.id','privileges','first_name','last_name','email','users.mobileno','permissions')
				->join('role_users','role_users.user_id','=','users.id')
				->where('id',$ID)		
				->first();
				if($record->permissions!=""){
					$companyArray 	= explode(",", $record->permissions);
				}
				// echo "<pre>";print_r($record);die;
			}
			$data =  User::select('users.full_name as name','users.profile_photo as pic','users.mobileno as contactno','users.email as emailadd','users.password as pswd')
			->where('id',$ID)->first();
			return View('admin.sub_admin.create',compact('data','route','PARENT_ID','companyArray','record','manager_name'));
		}
		$route=route('admin.sub_admin.store');
		return View('admin.sub_admin.create',compact('route','PARENT_ID','manager_name'));
	}
	public function change_password($id)
	{
		$detail=User::find($id);
		return view('admin.sub_admin.change_pass',compact('detail','id'));
	}
	public function change_password_post(Request $request,$id)
	{
		// die;
		$input=$request->all();
		$rules['password']    ='required|same:confirm_password';
		$rules['confirm_password']    ='required';
		$errorMsg="Opps ! Some Error Occured. Please Try Again.";
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
		}
		$detail=User::find($id);
		$user = Sentinel::findById($id);
		$user = Sentinel::update($user, ['password' => $request->get('password')]);
		$messgae= "Sub Admin Password Update Successfully";
		$output['status']='success';
		$output['msgType']='success';
		$output['msgHead']='Success!';
		$output['msg']=$messgae;
		$output['step']='step1';
		$output['success']=true;
		$output['slideToTop']=true;
		$output['success_msg']=$messgae;
		$output['url']=$this->manager_url;
		echo json_encode($output);die;
	}
	// public function assign_khagent($id=null)
	// {
	// 	$PARENT_ID=123;

	// 	$ids = $id;
	// 	$name =  SupportOfficers::select('users.full_name','users.id as userid','supportofficers.id as support_off_id')
	// 	->join('users','users.id','supportofficers.user_id')
	// 	->where('supportofficers.user_id',$id)->first();
	// 	// $kh_agent_data = KHAgent::select('users.full_name as kh_agent_name','users.mobileno as kh_agent_mobileno','users.district_id','khagent.id','khagent.support_officer_id','khagent.assiged_date')
	// 	// ->join('users','users.id','khagent.user_id')
	// 	// ->where('users.user_type','khagent')
	// 	// ->where('khagent.approval_status','Approved')
	// 	// ->where('support_officer_id','=', null)
	// 	// ->get();
	// 	$kh_agent_data = KHAgent::select('users.full_name as kh_agent_name','users.mobileno as kh_agent_mobileno','users.district_id','khagent.id','khagent.support_officer_id','khagent.assiged_date')
	// 	->join('users', 'users.id', 'khagent.user_id')
	// 	->where('users.user_type', 'khagent')
	// 	->where('khagent.approval_status', 'Approved')
	// 	->where('khagent.completed_steps', '1')
	// 	->where('support_officer_id','=', null)
	// 	->get();

	// 	$data = KHAgent::select('khagent.id as khid','khagent.user_id','users.full_name','users.mobileno','district.name as district_name','khagent.support_officer_id',DB::raw("(DATE_FORMAT(`tbl_khagent`.assiged_date,'%d %M  %Y')) as add_assiged_date"))
	// 	->leftJoin('users','users.id','khagent.user_id')
	// 	->leftJoin('district','district.id','users.district_id')
	// 	->where('support_officer_id',$id)
	// 	->get();

	// 	return view('admin.supportofficers.assign_khagent',compact('data','name','kh_agent_data','id','ids','PARENT_ID'));
	// }
	// public function data_assign_khagent($id=null)
	// {
	// 	$PARENT_ID=123;

	// 	$data = KHAgent::select('khagent.id as khid','khagent.user_id','users.full_name','users.mobileno','users.district_id','khagent.support_officer_id','khagent.assiged_date')
	// 	->left('users','users.id','khagent.user_id')
	// 	->where('support_officer_id',$id)
	// 	->get();
	// 	return Datatables::of($data)
	// 	->make(true);
	// }
	// public function store_assign_khagent(Request $request,$id=null)
	// {
	// 	$input = $request->all();
	// 	$update_data=KHAgent::where('id', $request->get('khagent_id'))
	// 	->update(['support_officer_id'=> $id]);
	// 	$messgae= " Support officer Assign Successfully";
	// 	$output['status']='success';
	// 	$output['step']='step1';
	// 	$output['success']=true;
	// 	$output['slideToTop']=true;
	// 	$output['success_msg']=$messgae;
	// 	$output['url']= route('admin.assign-khagent', ['id' => $id]);
	// 	echo json_encode($output);die;
	// }
	public function doTask(Request $request, $task, $ID,$s_id) {
		$manager_name = 'Support Officer';
		$str='Un-assign';
		$detail = KHAgent::Select('*')->where('id', $ID)->first();
		// $redirect_url =$_SERVER['HTTP_REFERER'];
		$taskP = $task = $str;
		$navi['route'] = route('assign-khagent.doTask', ['task' => $taskP, 'user_id' => $ID,'s_id'=>$s_id]);
		if (!empty($_POST)) {
			if ($request->get('confirm') == 'yes') {
				KHAgent::where('id', $ID)->update(['support_officer_id' => null]);
				$success = 'Un-assign Officer of Selected Record successfully.';
				return redirect(route('admin.assign-khagent', ['id' => $s_id]))->with('success', $success);
			}
		}
		return View('admin/layouts/active_inactive_view', compact('navi', 'detail', 'taskP', 'manager_name','str'));
	}
	public function getSubCategory()
	{    
		$cat = Input::get('cat');
		$subCat=BlogCategory::where('parent_id',$cat)->get();
		$output['subCat']	= $subCat;
		return response()->json($output);
	}
	public function store(Request $request, $ID=NULL)
	{

		$input=Input::all();
		// dd($input);
		$support_officer= 		$request->get('support_officer');
		$checksubadminVali	= ($support_officer=='' ? 'No' : 'Yes');
		$rules['full_name'] 		='required';
		$rules['mobileno'] 	='required';
		$rules['email'] 	='required';
		if($ID==null){
			$rules['email'] = 'required|email|unique:users,email';
			$rules['mobileno']    ='required|min:10|numeric|unique:users,mobileno';
			$rules['password'] ='min:6|required';
			$rules['profile_photo'] ='required|mimes:jpg,jpeg,png|max:1024';
		}else{
			$rules['email'] = 'required|email|unique:users,email,'.$ID; 
			$rules['mobileno']    ='required|min:10|numeric|unique:users,mobileno,'.$ID;
		}
		$errorMsg="Opps ! Some Error Occured. Please Try Again.";
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
		}
		$records=User::Select('users.id','users.profile_photo')->where('id',$ID)->first();   
		$input=$request->all();
		$input['password']=Crypt::encrypt($request->get('password'));

		if($ID!=''){
			$data=User::find($ID);
			if(count($data)==0){
				$messgae="This is not valid action, data not found.";
				return Redirect::route('admin.sub_admin')->with('error', trans($messgae));
			}
		}
		if(isset($ID) && $ID!=''){		
			$records=User::Select('profile_photo')->where('id',$ID)->first();  
			// dd($request->file('profile_photo'));
			if (($request->file('profile_photo')!=null) && $file = $request->file('profile_photo')){
				$fileName = $file->getClientOriginalName();
				$extension = $file->getClientOriginalExtension();
				$folderName = 'user';
				$safeName = str_random(10) . '.' . $extension;
				@mkdir($folderName,0777,true);
				@chmod($folderName,0777);
				Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
				$profile_photo = $safeName;
				if(count($records)>0 && ($records->profile_photo!='') && ($profile_photo!=''))
				{
					$folderName = 'user';
					$filedir = $folderName .'/'. $records->profile_photo;
					Storage::disk('uploads')->delete($filedir);
				}
			}
			else
			{
				$profile_photo = $records->profile_photo;
			}	
		  //================= Update Client Data ========//

			$update_data=User::where('id', $ID)
			->update([ 
				'email'				=> $request->get('email'),						
			// 'password'				=> $request->get('password'),						
			//	'password'				=> $input['password'],						
				'mobileno'				=> $request->get('mobileno'),						
				'profile_photo'				=> $profile_photo,						
				'full_name'				=> $request->get('full_name'),						
				'add_from'			=> 'Admin',	
			]);
			$role_user['privileges']=json_encode($request->get('support_officer'));
			$role_user = RoleUser::where('user_id',$ID)->update(['privileges' => $role_user['privileges']]);
		}
		else{
			
			if ( $file = $request->file('profile_photo')){
				$fileName = $file->getClientOriginalName();
				$extension = $file->getClientOriginalExtension();
				$folderName = '/user';
				$safeName = str_random(10) . '.' . $extension;
				@mkdir($folderName,0777,true);
				@chmod($folderName,0777);
				Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
				$input['profile_photo'] = $safeName;
			}
			$storearray['user_type']="sub_admin";
			$storearray['email']		= $request->get('email');
			$storearray['password']			= $request->get('password');
			$storearray['verification_status']			= 'Yes';
			$storearray['mobileno']			= $request->get('mobileno');
			$storearray['profile_photo']			= $input['profile_photo'];
			$storearray['full_name']		= $request->get('full_name');
			$storearray['add_from']			= 'Admin';
			$so_role=Roles::select('id')->where('slug','sub-admin')->first();
			$activate = $this->user_activation;
			$user = Sentinel::register($storearray, $activate);
			$role_user = new RoleUser();
			$role_user->user_id=$user->id; 
			$role_user->role_id=$so_role->id;
			$role_user->privileges=json_encode($request->get('support_officer'));
			$role_user->save();
		// if($ID==NULL){
		// 	$customer = new SupportOfficers();
		// 	$customer->user_id =$user->id;
		// 	$customer->save();
		// }
		}
		if($ID!=NULL)
			$messgae= " Sub Admin Updated Successfully";
		else
			$messgae= "  Sub Admin Created Successfully";
	// if ($user->save()) {
	//app_modal::where('id', $tournament->id);
		$output['status']='success';
		$output['msgType']='success';
		$output['msgHead']='Success!';
		$output['msg']=$messgae;
		$output['step']='step1';
		$output['success']=true;
		$output['slideToTop']=true;
		$output['success_msg']=$messgae;
		$output['url']=$this->manager_url;
		echo json_encode($output);die;
	// } else {
	// return redirect('admin/customer/create')->with('error', 'Oops! Something went wrong.');
	// }
	}
	public function view($id){
		$PARENT_ID=123;
		$detail	= User::select('users.id as userid','users.full_name as name','users.ip as ip_add','users.profile_photo as pic','users.ip as ip_address','users.mobileno as contactno','users.email as emailadd'
			,DB::raw("(DATE_FORMAT(`tbl_users`.created_at,'%d %M  %Y')) as add_date"),DB::raw("(DATE_FORMAT(`tbl_users`.updated_at,'%d %M  %Y')) as update_date"))
		->where('users.id',$id)->first();
		if(count($detail)==0){
			$notification = array(
				'message' =>  'Data Not Found.', 
				'alert-type' => 'warning'
			);
			return redirect('securekhcpmin/sub_admin')->with($notification);
		}
		return view('admin.sub_admin.show',compact('PARENT_ID','detail'));
	}
	public function getModalDelete($id = null)
	{
		$model = 'Sub Admin';
		$confirm_route = $error = null;
		$tripmanagers= User::where('id', $id)->first();
		if (empty($tripmanagers)) {
			return Redirect::route('info');
		}
		else
		{
			$confirm_route = route('delete/sub_admin', ['id' => $id]);
			return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
		}
	}
	public function destroy($id)
	{
		$user = User::where('id', $id)->first();
		// $activation = Activations::where('user_id',$id);
		// $roleusers = RoleUser::where('user_id',$id);
		$res=$user->delete();
		// $res=$supportofficers->delete();	
		// $res=$activation->delete();	
		// $res=$roleusers->delete();	
		return Redirect::route('admin.sub_admin');
	}
	  //========================================Support  Officers  TRASH====================================================
	public function listDeletedtrash()
	{ 
		$request_url_user = 'Trip Trash Manager ';
		$PARENT_ID = 156;
		return view('admin.supportofficers_trash.deletedlist',compact('PARENT_ID','request_url_user'));
	}
	public function listDeletedData()
	{
		$enquiry = User::select(['id','users.full_name as name','users.mobileno as mobile_no','users.email as email_address',DB::raw("DATE_FORMAT(created_at, '%d %M  %Y') as add_date")])->orderBy('id', 'desc')->where('users.user_type','supportofficer')->onlyTrashed()->get();
			// print_r($enquiry);die;
		return Datatables::of($enquiry)
		->addColumn('actions','<div class="btn-group">
			<a data-toggle="modal" data-target="#modal-large" href="{{URL::to("securekhcpmin/supportofficers_trash/$id/confirm-restore")}}" class="btn btn-small btn-default"title="Restore"><i class="fa fa-undo"> Restore</i></a>
			<a data-toggle="modal" data-target="#modal-large" href="{{URL::to("securekhcpmin/supportofficers_trash/$id/final-delete")}}" class="delval btn btn-small btn-danger" title="Permanent Delete"><i class="fa fa-trash"> Delete</i></a></div>
			') ->rawColumns(['actions'])->make(true);
	}
	public function getModalRestore($id = null)
	{
		$model = 'SupportOfficers Record';
		$confirm_route = $error = null;
		$confirm_route = route('restore/supportofficers_trash', ['id' => $id]);
		return View('admin/layouts/restore_modal_confirmation', compact('error', 'model', 'confirm_route'));
	}
	public function restoreDeletedtrash($enu_id)
	{
		User::withTrashed()->where('id',$enu_id)->restore();
		SupportOfficers::withTrashed()->where('user_id',$enu_id)->restore();
		$success="Trash Record Restore Successfully";
		return Redirect::route('supportofficers_trash/deletedfeedback')->with('success', $success);
	}
	public function getModalFinalDelete($id = null)
	{
		$model = 'Trash Record';
		$confirm_route = $error = null;
		$confirm_route = route('finaldelete/supportofficers_trash', ['id' => $id]);
		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
	}
	public function permanentDelete($id)
	{
		$detail=User::select('*') ->where('users.id',$id)->onlyTrashed()->first();
			// echo($detail->profile_photo);die;
		if(isset($detail->profile_photo))
		{
			$folderName = '/user';
			$filedir = $folderName .'/'. $detail->profile_photo;
			$query=Storage::disk('uploads')->delete($filedir);
				// echo($filedir);die;
		}
		User::where('id',$id)->withTrashed()->forceDelete();
		SupportOfficers::where('user_id',$id)->withTrashed()->forceDelete();
		$success="Trash Record Permanently Deleted Successfully";
		return Redirect::route('supportofficers_trash/deletedfeedback')->with('success', $success);
	}
}
