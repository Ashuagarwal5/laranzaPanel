<?php
namespace App\Http\Controllers\Admin;
use Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use View;
use App\Notification as app_modal;
use App\User;
use App\KHAgent;
use App\Roles;
use App\Notification;
use Validator;
use DB;
use Datatables;
use Redirect;
use Sentinel;
use App\Helpers\datehelper;
use Illuminate\Support\Str;

class NotificationController extends Controller{
	function __construct()
	{
		$this->date=datehelper::dateformat();
		$this->manager_name  = 'Notifications';
		$this->folder_name   = 'notifications';
		$this->manager_url   = route('admin.notifications');
		$this->route_data      = route('admin.notifications.data');
		$this->route_create      = route('create.notifications');

	}
	//================== Admin   View Function ==================//
	public function index(){
		$manager_name  = $this->manager_name;
		$PARENT_ID     = 121;
		$route_data    = $this->route_data;
		$route_create    = $this->route_create;
		$folder_name    = $this->folder_name;
		// $data =app_modal::select('*')
		// ->orderBy('id', 'desc')->get();
		// echo "<pre>";print_r($data);die;
		return view('admin.'.$this->folder_name.'.list',compact('PARENT_ID','folder_name','manager_name','route_create','route_data'));
	}
	public function data()
	{
		$data =app_modal::select('notifications.*','users.full_name as user_name')
		->leftJoin('users','users.id','notifications.user_id')
		->orderBy('notifications.id', 'desc')->get();
		return Datatables::of($data)
		->addColumn('actions', '<div class="btn-group">
			<a class="btn btn-primary" href="{{URL::to("cpmin/notifications/edit/$id")}}" title="Edit"><i class="fa fa-edit"></i> </a>
			<a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="{{URL::to("cpmin/notifications/$id/confirm-delete")}}" title="Delete"><i class="fa fa-trash"></i> </a>
			</div>
			')
		->rawColumns(['actions'])
		->make(true);
	}
	public function create($id=null)
	{
		$manager_name  = $this->manager_name;
		$PARENT_ID     = 121;
		$route_data    = $this->route_data;
		$route_create    = $this->route_create;
		$manager_url=$this->manager_url;
		$folder_name    = 'Change Status';
		$all_roles=Roles::select('id','name','slug')->where('id','!=',2)->where('id','!=',3)->get();
		$data=app_modal::select('*')->where('id',$id)->first();
		// $users=User::select('id','full_name')->where('user_type','user')->where('verification_status','Approve')->get();
		$users=User::select('role_users.user_id','role_users.role_id','users.id','users.full_name')->join('role_users','role_users.user_id','users.id')->join('roles','roles.id','role_users.role_id')->where('users.deleted_at',null)->where('roles.slug','user')->get();
		return view('admin.'.$this->folder_name.'.create',compact('data','PARENT_ID','folder_name','manager_name','route_create','users','route_data','all_roles','manager_url'));
	}
	public function getModalDelete($id = null)
	{
		$model = 'Notification';
		$confirm_route = $error = null;
		$confirm_route = route('delete.notifications', ['id' => $id]);
		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
	}
	public function destroy($id)
	{
		$data = app_modal::where('id',$id)->delete();
		$success ="Notification Deleted Successfully.";
		$route = route('admin.notifications');
		return Redirect($route)->with('success', $success);
	}
	public function store(Request $request,$id=null)
	{
		$input=Input::all();
		// echo "<pre>";print_r($input);die;
		$rules['title'] ='required';
		$rules['description'] ='required';
		$rules['type'] ='required';
		if ($request->image != null) {
			$rules['image'] ='mimes:jpeg,jpg,png,gif|required|max:2048';
		}

		
		$rules['user_id'] ='required_if:send_type,==,individual';
		$msg['user_id.required']='The customer field is required.';	
		
		//$rules['user_id'] ='required_if:type,==,user';
		//$msg['user_id.required']='The customer field is required.';





		$errorMsg="Opps ! Some Error Occured. Please Try Again.";
		$validator = Validator::make($input, $rules,$msg);
		if ($validator->fails()) {
			return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
		}
		$records= app_modal::Select('id','image')->where('id',$id)->first();   
		if($id == null){
			$data = new Notification;
			if ($request->user_id == null) {
				$data->user_id = 0;
			}
			else {
				$data->user_id = $request->user_id;
			}

		}
		else{
			$data = Notification::where('id',$id)->first();

		}
		if ($request->image != null) {
			if ($file = $request->file('image')){
				$fileName = $file->getClientOriginalName();
				$extension = $file->getClientOriginalExtension();
				$folderName = '/notifications';
				$safeName = Str::random(10) . '.' . $extension;
				Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
				$data->image = $safeName;
			}
			else
			{
				$data->image = $records->image;
			}
		}
		
		
			$data->title = $request->title;
			$data->description = $request->description;
			$data->send_type = $request->send_type;
			$data->type = $request->type;
			$data->status = "Pending";
			$data->save();
		
		// $data=app_modal::updateOrCreate(['id' => $id],$input);

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
		$output['url'] = route('admin.notifications');
		return response()->json($output);
		
		
	}
}
