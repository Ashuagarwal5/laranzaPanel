<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\datehelper;
use App\Roles;
use App\RoleUser;
use App\User as app_modal;
use App\CustomerPoints;
use App\States;
use App\City;
use App\Products;
use App\SendMessage;
use App\Bank_detail;
use App\Document;
use App\WebsiteSetting;
use App\User;
use App\Dealer;
use Crypt;
use Datatables;
use DB;
use Illuminate\Http\Request;
use Input;
use Illuminate\Support\Facades\Storage;
use Redirect;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Validator;
use View;
use Artisan;
use App\PushNotification;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private $user_activation = true;

    public function __construct()
    {
        $this->date = datehelper::dateformat();
        $this->manager_name = 'User';
        $this->approved_customers = 'Approved Customers';
        $this->pending_customers = 'Pending Customers';
        $this->reject_customers = 'Rejected Customers';
        $this->folder_name = 'user';
        $this->manager_url = route('admin.user');
        $this->store_url = route('user.store');
    }

    //********************************Approved Customer Section Start Here ******************************/
    public function approve_customer()
    {
        $approved_customers = $this->approved_customers;
        $PARENT_ID = 1;
        $route_data = route('admin.user.approvecustomerdata');
        return view('admin.' . $this->folder_name . '.approve_customer', compact('PARENT_ID', 'approved_customers', 'route_data'));
    }

    public function approvecustomerdata(Request $request)
    {
        // $data = app_modal::select(['users.*', 'dealers.dealer_name',DB::raw("DATE_FORMAT(tbl_users.created_at,'%d %M  %y') as add_date")]);
        // $data =	$data->leftjoin('role_users', 'role_users.user_id', '=', 'users.id');
        // $data =	$data->leftjoin('roles', 'roles.id', '=', 'role_users.role_id');
        // $data =	$data->leftjoin('dealers', 'users.dealer_id', '=', 'dealers.user_id');
        // $temp =		$data->where('roles.slug', 'user');

        $data = app_modal::where('profile_status','=','Approve')->select(['users.*', 'dealers.dealer_name', DB::raw("DATE_FORMAT(tbl_users.created_at,'%d %M  %y') as add_date")]);
        $data =	$data->leftjoin('role_users', 'role_users.user_id', '=', 'users.id');
        $data =	$data->leftjoin('roles', 'roles.id', '=', 'role_users.role_id');
        $data =	$data->leftjoin('dealers', 'users.dealer_id', '=', 'dealers.user_id');
        $temp = $data->where('roles.slug', 'user');
        if (!empty($request->user_type)) {
            $temp = $temp->where('users.user_type', $request->user_type);
        }
        if (!empty($request->fullname)) {
            $temp = $temp->where('users.full_name', 'like', '%' . $request->fullname . '%');
        }
        if (!empty($request->mobileno)) {
            $temp = $temp->where('users.mobileno', $request->mobileno);
        }
        $temp = $temp->orderBy('id', 'desc');
        $data = $temp->get();

        return Datatables::of($data)
        ->addColumn('actions', '<div class="btn-group">
            <a title="View" class="btn btn-success" href="{{ route("user.approve_customer_view",$id) }}" data-toggle="modal" data-target="#modal-email"><i class="fa fa-eye"></i></a>
            <a class="btn btn-primary" href="{{ route("admin.user.edit",$id)}}" title="Edit"><i class="fa fa-edit"></i> </a>
            <a data-original-title="Delete Record"   class="btn btn-danger enable-tooltip" href="{{ route("confirm-delete/admin_user",$id) }}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-trash"></i></a>
            <a title="Reward Points" class="btn btn-success" href="{{ route("user.rewards",[$id,"All"]) }}" ><i class="fa fa-wallet"></i>Reward Points</a>
			@if($profile_status=="Approve")
			<a title="Pending" class="btn btn-success" href="{{route("confirm.approve.pending",[$id])}}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-check"></i></a>
			@else
			<a title="Approve" class="btn btn-warning" href="{{route("confirm.approve.pending",[$id])}}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-check"></i></a>
			@endif
            @if($status == "Active")
            <a title="Block" data-original-title="Block"   class="btn btn-danger enable-tooltip butt_change" href="{{ route("confirm.approve.status",[$id]) }}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-lock  "></i></a>
            @else
            <a title="UnBlock" data-original-title="UnBlock"   class="btn btn-info enable-tooltip" href="{{ route("confirm.approve.status",[$id]) }}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-unlock"></i></a>
            @endif
			</div>
			</div>
        ')
        ->rawColumns(['actions'])
        ->make(true);
    }

    public function approve_customer_view($ID)
    {
        $approved_customers = $this->approved_customers;
        $user = app_modal::select('*', DB::raw("DATE_FORMAT(created_at,'%d %M  %Y') as add_date"), DB::raw("DATE_FORMAT(updated_at,'%d %M  %Y') as update_date"))->where('id', $ID)->first();
        if (isset($user->dealer_id) && ($user->dealer_id)!='') {
            $user->dealer_name = app_modal::where('id', $user->dealer_id)->first()->full_name;
        }
        if (isset($user->dealer_id) && ($user->dealer_id)!='') {
            $dealerRecord = Dealer::select('dealer_name')->where('user_id', $user->dealer_id)->first();
            if(isset($dealerRecord)) $user->dealer_name = $dealerRecord->dealer_name; else $user->dealer_name = 'NA';
        }
        $complete_bank_details = Bank_detail::where('user_id', $ID)->orderBy('id', 'DESC')->get();
        $document_details = Document::where('user_id', $ID)->orderBy('id', 'DESC')->get();
        $show_action = 1;
        return View('admin.' . $this->folder_name . '.approve_customer_view', compact('user', 'approved_customers', 'show_action','complete_bank_details','document_details'));
    }

    // public function approveCustomerDelete($id = null)
    // {
    //     $model = $this->approved_customers;
    //     $confirm_route = $error = null;
    //     $confirm_route = route('delete/approve_customer', ['id' => $id]);
    //     return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
    // }

    // public function destroyapprovecustomer($id = null)
    // {
    //     $detail = app_modal::where('id', $id)->first();
    //     app_modal::where('id', $id)->delete();
    //     $success = $this->approved_customers . " Deleted Succesfully";
    //     return redirect($this->manager_url)->with('success', $success);
    // }
    //*****************************Approved Customer Section End Here ********************************** */

    //********************************Pending Customer Section Start Here ******************************/
    public function pending_customer()
    {
        $pending_customers = $this->pending_customers;
        $PARENT_ID = 1;
        $route_data = route('admin.user.pendingcustomerdata');
        return view('admin.' . $this->folder_name . '.pending_customer', compact('PARENT_ID', 'pending_customers', 'route_data'));
    }

    public function pendingcustomerdata(Request $request)
    {
        $data = app_modal::where('profile_status','=','pending')->select(['users.*', DB::raw("DATE_FORMAT(tbl_users.created_at,'%d %M  %y') as add_date")]);
        $data =	$data->leftjoin('role_users', 'role_users.user_id', '=', 'users.id');
        $data =	$data->leftjoin('roles', 'roles.id', '=', 'role_users.role_id');
        $temp =		$data->where('roles.slug', 'user');
        if (!empty($request->user_type)) {
            $temp = $temp->where('users.user_type', $request->user_type);
        }
        if (!empty($request->fullname)) {
            $temp = $temp->where('users.full_name', 'like', '%' . $request->fullname . '%');
        }
        if (!empty($request->mobileno)) {
            $temp = $temp->where('users.mobileno', $request->mobileno);
        }
        $temp = $temp->orderBy('id', 'desc');
        $data = $temp->get();
        // dd($data);
        return Datatables::of($data)
        ->addColumn('actions', '<div class="btn-group">
        <a title="View" class="btn btn-success" href="{{ route("user.pending_customer_view",$id) }}" data-toggle="modal" data-target="#modal-email"><i class="fa fa-eye"></i></a>
        <a class="btn btn-primary" href="{{ route("admin.user.edit",$id)}}" title="Edit"><i class="fa fa-edit"></i> </a>

            <a data-original-title="Delete Record"   class="btn btn-danger enable-tooltip" href="{{ route("confirm-delete/admin_user",$id) }}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-trash"></i></a>
            @if($profile_status=="Approve")
        <a title="Pending" class="btn btn-success" href="{{route("confirm.approve.pending",[$id])}}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-check"></i></a>
        @else
        <a title="Approve" class="btn btn-warning" href="{{route("confirm.approve.pending",[$id])}}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-check"></i></a>
        @endif
            </div>
            </div>
            ')
        ->rawColumns(['actions'])
        ->make(true);
    }
    
    //  @if($step_completed > 0 && $step_completed < 3)
    //  <a title="Step By Step" class="btn btn-info" href="{{route("user.step-update",[$id])}}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-step-forward"></i></a>
    //  @endif

    public function pending_customer_view($ID)
    {
        $pending_customers = $this->pending_customers;
        $user = app_modal::select('*', DB::raw("DATE_FORMAT(created_at,'%d %M  %Y') as add_date"), DB::raw("DATE_FORMAT(updated_at,'%d %M  %Y') as update_date"))->where('id', $ID)->first();
        if (isset($user->dealer_id) && ($user->dealer_id)!='') {
            $dealer_name = app_modal::where('id', $user->dealer_id)->first();
            if($dealer_name){
            $user->dealer_name = $dealer_name->full_name;
            }
            else{
                $user->dealer_name = "";
            }
        }
        $complete_bank_details = Bank_detail::where('user_id', $ID)->orderBy('id', 'DESC')->get();
        $document_details = Document::where('user_id', $ID)->orderBy('id', 'DESC')->get();
        $show_action = 1;
        return View('admin.' . $this->folder_name . '.pending_customer_view', compact('user', 'pending_customers', 'show_action','complete_bank_details','document_details'));
    }
    //*****************************Pending Customer Section End Here ********************************** */

    public function reject_customer()
    {
        $reject_customers = $this->reject_customers;
        $PARENT_ID = 1;
        $route_data = route('admin.user.rejectcustomerdata');
        return view('admin.' . $this->folder_name . '.reject_customer', compact('PARENT_ID', 'reject_customers', 'route_data'));
    }

    public function rejectcustomerdata(Request $request)
    {
        $data = app_modal::where('profile_status','=','Reject')->select(['users.*', DB::raw("DATE_FORMAT(tbl_users.created_at,'%d %M  %y') as add_date")]);
        $data =	$data->leftjoin('role_users', 'role_users.user_id', '=', 'users.id');
        $data =	$data->leftjoin('roles', 'roles.id', '=', 'role_users.role_id');
        $temp =		$data->where('roles.slug', 'user');
        if (!empty($request->user_type)) {
            $temp = $temp->where('users.user_type', $request->user_type);
        }
        if (!empty($request->fullname)) {
            $temp = $temp->where('users.full_name', 'like', '%' . $request->fullname . '%');
        }
        if (!empty($request->mobileno)) {
            $temp = $temp->where('users.mobileno', $request->mobileno);
        }
        $temp = $temp->orderBy('id', 'desc');
        $data = $temp->get();

        return Datatables::of($data)
        ->addColumn('actions', '<div class="btn-group">
        <a title="View" class="btn btn-success" href="{{ route("user.reject_customer_view",$id) }}" data-toggle="modal" data-target="#modal-email"><i class="fa fa-eye"></i></a>
            </div>
            </div>
            ')
        ->rawColumns(['actions'])
        ->make(true);
    }

    public function reject_customer_view($ID)
    {
        $reject_customers = $this->reject_customers;
        $user = app_modal::select('*', DB::raw("DATE_FORMAT(created_at,'%d %M  %Y') as add_date"), DB::raw("DATE_FORMAT(updated_at,'%d %M  %Y') as update_date"))->where('id', $ID)->first();
        if (isset($user->dealer_id) && ($user->dealer_id)!='') {
            $user->dealer_name = app_modal::where('id', $user->dealer_id)->first()->full_name;
        }
        $complete_bank_details = Bank_detail::where('user_id', $ID)->orderBy('id', 'DESC')->get();
        $document_details = Document::where('user_id', $ID)->orderBy('id', 'DESC')->get();
        $show_action = 1;
        return View('admin.' . $this->folder_name . '.reject_customer_view', compact('user', 'reject_customers', 'show_action','complete_bank_details','document_details'));
    }

    //================== Admin   View Function ==================//
    public function index()
    {
        // Artisan::call('config:clear');
        $manager_name = $this->manager_name;
        $PARENT_ID = 1;
        $route_create = route('create.user');
        $route_data = route('admin.user.data');
        $dealer = Dealer::get();
        return view('admin.' . $this->folder_name . '.list', compact('PARENT_ID', 'manager_name', 'route_create', 'route_data','dealer'));
    }

    public function data(Request $request)
    {
        $data = app_modal::select(['users.*', 'dealers.dealer_name',DB::raw("DATE_FORMAT(tbl_users.created_at,'%d %M  %y') as add_date")]);
        $data =	$data->leftjoin('role_users', 'role_users.user_id', '=', 'users.id');
        $data =	$data->leftjoin('roles', 'roles.id', '=', 'role_users.role_id');
        $data =	$data->leftjoin('dealers', 'users.dealer_id', '=', 'dealers.user_id');
        $temp = $data->where('roles.slug', 'user');
        if (!empty($request->user_type)) {
            $temp = $temp->where('users.user_type', $request->user_type);
        }
        if (!empty($request->fullname)) {
            $temp = $temp->where('users.full_name', 'like', '%' . $request->fullname . '%');
        }
        if (!empty($request->mobileno)) {
            $temp = $temp->where('users.mobileno', $request->mobileno);
        }
        if (!empty($request->dealer_id)) {
            $temp = $temp->where('dealers.id', $request->dealer_id);
        }
        $temp = $temp->orderBy('id', 'desc');
        $data = $temp->get();

        /*	This commented code is related to User Active Inactive Block and Unbloc==#
            @if($status=="Active")
                <a title="Inactive" class="btn btn-success" href="{{route("confirm.active.inactive.user",[$id])}}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-circle-o"></i></a>
            @else
                <a title="Active" class="btn btn-warning" href="{{route("confirm.active.inactive.user",[$id])}}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-circle-o"></i></a>
            @endif
        */
        return Datatables::of($data)
        ->addColumn('actions', '<div class="btn-group">
			<a title="View" class="btn btn-success" href="{{ route("user.show",$id) }}" data-toggle="modal" data-target="#modal-email"><i class="fa fa-eye"></i></a>
			<a class="btn btn-primary" href="{{ route("admin.user.edit",$id)}}" title="Edit"><i class="fa fa-edit"></i> </a>
			<a data-original-title="Delete Record"   class="btn btn-danger enable-tooltip" href="{{ route("confirm-delete/admin_user",$id) }}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-trash"></i></a><hr/>
			<a title="Reward Points" class="btn btn-success" href="{{ route("user.rewards",[$id,"All"]) }}" ><i class="fa fa-wallet"></i>Reward Points</a>

			@if($profile_status=="Approve")
			<a title="Pending" class="btn btn-success" href="{{route("confirm.approve.pending",[$id])}}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-check"></i></a>
			@else
			<a title="Approve" class="btn btn-warning" href="{{route("confirm.approve.pending",[$id])}}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-check"></i></a>
			@endif
			</div>
			</div>
        ')
        ->rawColumns(['actions'])
        ->make(true);
    }

    public function create($ID = null)
    {
        $manager_name = $this->manager_name;
        $navi['back_url'] = $this->manager_url;
        $PARENT_ID = 1;
        $state = States::select('id', 'name')
        ->where('status', 'Active')
        ->orderBy('name', 'asc')->withTrashed()
        ->get();
        $district = City::select('id', 'name')
        ->where('status', 'Active')
        ->orderBy('name', 'asc')
        ->get();
        $all_roles=Roles::select('id', 'name')->where('id', '!=', 2)->where('id', '!=', 3)->where('id', '!=', 8)->get();
        //echo "<pre>";print_r($all_roles);die;
        $dealers=app_modal::select('id', 'full_name', 'mobileno')->where('user_type', 'Dealer')->get();
        if ($ID) {
            $navi['route'] = route('admin.user.edit', ['bcat_id' => $ID]);
            //$data=app_modal::find($ID);
            $data = app_modal::select('users.*')
            ->where('id', $ID)
            ->first();
            $data->bank_details = Bank_detail::where('user_id', $data->id)->first();
            $data->document_details = Document::where('user_id', $data->id)->first();

            $complete_bank_details = Bank_detail::where('user_id', $data->id)->orderBy('id', 'DESC')->get();
            $document_details = Document::where('user_id', $data->id)->orderBy('id', 'DESC')->get();
            $show_action = 0;

            if (count(array($data)) == 0) {
                $messgae = "This is not valid action, data not found.";
                return redirect($this->manager_url)->with('error', trans($messgae));
            }
            $user_data = app_modal::find($ID);
            $data->email = $user_data->email;
            $data->mobileno = $user_data->mobileno;
            $data->full_name = $user_data->full_name;
            $data->city = $user_data->city;
            $data->state = $user_data->state;
            // echo "<pre>";
            // print_r($data);
            // echo "<pre>";
            // die();
            //$data =  app_modal::where('id',$ID)->first();
            return View('admin.' . 'user' . '.create', compact('navi', 'data', 'PARENT_ID', 'manager_name', 'state', 'district', 'all_roles', 'dealers', 'complete_bank_details', 'document_details', 'show_action'));
        }
        //$route= $this->store_url;
        $navi['route'] = $this->store_url;
        return View('admin.' . 'user' . '.create', compact('navi', 'PARENT_ID', 'manager_name', 'state', 'district', 'all_roles', 'dealers'));
    }

    public function store(Request $request, $id = null)
    {
        $input = Input::all();
        // dd($input['payment_type']);
        if (empty($id)) {    //Save
            if ($request->full_name) {
                $rules['full_name'] 	= 'required';
                $rules['email'] 		= "required|email|unique:users,email";
                $rules['mobileno'] 		= "required|digits:10|unique:users,mobileno";
                $rules['address'] 		= "required";
                $rules['city'] 			= "required";
                $rules['state'] 		= "required";
                $rules['user_type'] 	= "required";
                $rules['pincode'] 		= "digits:6";
              //  $rules['referral_code'] 		= "required";
            //$rules['dealer_id'] = "required_unless:user_type,Dealer";
            } elseif ($request->document_name) {
                $rules['document_name'] 	= 'required';
                $rules['status'] 		= "required";
                $rules['document_image_1'] 		= "required|mimes:jpeg,jpg,png,gif";
            } else {
                $rules['payment_type'] 	= 'required';
                $rules['status'] 		= "required";
            }
        } else {			//Edit
            if ($request->full_name == 'document_type') {
                $rules['document_name'] 	= "required";
                $rules['status'] 		= "required";
            } elseif ($request->full_name == 'bank_details') {
                $rules['payment_type'] 	= 'required';
                $rules['status'] 		= "required";
            } else {
                $rules['full_name'] 	= 'required';
                $rules['address'] 		= "required";
                $rules['city'] 			= "required";
                $rules['state'] 		= "required";
                $rules['user_type'] 	= "required";
                $rules['pincode'] 		= "digits:6";
               // $rules['referral_code'] 		= "required";

            }
        }
        $msg['user_type.required']='The User Role field is required.';
        //$msg['dealer_id.required']='The Dealer field is required.';

        $errorMsg = "Opps ! Some Error Occured. Please Try Again.";

        $validator = Validator::make($input, $rules, $msg);
        if ($validator->fails()) {
            return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
        }

        $records = app_modal::Select('profile_photo')->where('id', $id)->first();
        $records1 = Document::Select('document_image_1')->where('user_id', $id)->first();
        $records2 = Document::Select('document_image_2')->where('user_id', $id)->first();
        // echo "<pre>";print_r($request->all());die;

        if ($request->full_name == 'document_type') {
            // 		$document = Document::where('user_id',$id)->first();
            // 		if ($document) {
            // 			$document->document_name = $request->document_name;
            // 		$document->status = $request->status;
            // 		$document->user_id = $id;
            // 		if ($file = $request->file('document_image_1'))
            // 	{
            // 		$fileName = $file->getClientOriginalName();
            // 		$extension = $file->getClientOriginalExtension();
            // 		$folderName = '/Document Image';
            // 		$safeName = Str::random(10) . '.' . $extension;
            // 		@mkdir($folderName, 0777, true);
            // 		@chmod($folderName, 0777);
            // 		Storage::disk('uploads')->putFileAs($folderName, $file, $safeName);
            // 		$document->document_image_1 = $safeName;

            // 	}
            // 	if ($file = $request->file('document_image_2'))
            // 	{
            // 		$fileName = $file->getClientOriginalName();
            // 		$extension = $file->getClientOriginalExtension();
            // 		$folderName = '/Document Image';
            // 		$safeName = Str::random(10) . '.' . $extension;
            // 		@mkdir($folderName, 0777, true);
            // 		@chmod($folderName, 0777);
            // 		Storage::disk('uploads')->putFileAs($folderName, $file, $safeName);
            // 		$document->document_image_2 = $safeName;

            // 	}
            // }
            // 	else {
            $document = new Document();
            $document->document_type = $request->document_name;
            $document->status = $request->status;
            $document->user_id = $id;

            if ($file = $request->file('document_image_1')) {
                $fileName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $folderName = '/documents';
                $safeName = Str::random(10) . '.' . $extension;
                @mkdir($folderName, 0777, true);
                @chmod($folderName, 0777);
                Storage::disk('uploads')->putFileAs($folderName, $file, $safeName);
                $document->document_image_1 = $safeName;
            }
            if ($file = $request->file('document_image_2')) {
                $fileName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $folderName = '/documents';
                $safeName = Str::random(10) . '.' . $extension;
                @mkdir($folderName, 0777, true);
                @chmod($folderName, 0777);
                Storage::disk('uploads')->putFileAs($folderName, $file, $safeName);
                $document->document_image_2 = $safeName;
            }
            // }

            $document->save();
        // else {
        // 	$document = new Document;
        // 	$document->document_name = $request->document_name;
        // 	$document->status = $request->status;
        // 	$document->user_id = $id;
        // 	if ($file = $request->file('document_image_1'))
        // {
        // 	$fileName = $file->getClientOriginalName();
        // 	$extension = $file->getClientOriginalExtension();
        // 	$folderName = '/Document Image';
        // 	$safeName = Str::random(10) . '.' . $extension;
        // 	@mkdir($folderName, 0777, true);
        // 	@chmod($folderName, 0777);
        // 	Storage::disk('uploads')->putFileAs($folderName, $file, $safeName);
        // 	$document->document_image_1 = $safeName;
        // }
        // if ($file = $request->file('document_image_2'))
        // {
        // 	$fileName = $file->getClientOriginalName();
        // 	$extension = $file->getClientOriginalExtension();
        // 	$folderName = '/Document Image';
        // 	$safeName = Str::random(10) . '.' . $extension;
        // 	@mkdir($folderName, 0777, true);
        // 	@chmod($folderName, 0777);
        // 	Storage::disk('uploads')->putFileAs($folderName, $file, $safeName);
        // 	$document->document_image_2 = $safeName;
        // }
        // }
        } elseif ($request->full_name == 'bank_details') {
            // if(!empty($id)){
            // 	$details = Bank_detail::where('user_id',$id)->first();
            // 	if ($details) {
            // 		$details->payment_type = $request->payment_type;
            // 		$details->bank_name = $request->bank_name;
            // 		$details->branch_name = $request->branch_name;
            // 		$details->ifsc_code = $request->ifsc_code;
            // 		$details->account_number = $request->account_number;
            // 		$details->upi_id = $request->upi_id;
            // 		$details->status = $request->status;
            // 		$details->user_id = $id;
            // 		$details->save();
            // 	}
            // 	else {
            $details = new Bank_detail();
            $details->payment_type = $request->payment_type;
            $details->bank_name = $request->bank_name;
            $details->branch_name = $request->branch_name;
            $details->ifsc_code = $request->ifsc_code;
            $details->account_number = $request->account_number;
            $details->upi_id = $request->upi_id;
            $details->gpay_number = $request->gpay_number;
            $details->status = $request->status;
            $details->user_id = $id;
            $details->save();
        // 	}
            // }
            // else{
                    // $details = new Bank_detail;
                    // $details->payment_type = $request->payment_type;
                    // $details->bank_name = $request->bank_name;
                    // $details->branch_name = $request->branch_name;
                    // $details->ifsc_code = $request->ifsc_code;
                    // $details->account_number = $request->account_number;
                    // $details->upi_id = $request->upi_id;
                    // $details->status = $request->status;
                    // $details->user_id = $id;
                    // $details->save();
            // }
        } else {
            if ($file = $request->file('profile_photo')) {
                $fileName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $folderName = '/user';
                $safeName = Str::random(10) . '.' . $extension;
                @mkdir($folderName, 0777, true);
                @chmod($folderName, 0777);
                Storage::disk('uploads')->putFileAs($folderName, $file, $safeName);
                $input['profile_photo'] = $safeName;
            }


            if (!empty($id)) { //Edit User
                if ($file = $request->file('profile_photo')) {
                    $update_data = app_modal::where('id', $id)
                ->update([
                            'full_name' 	=> $request->get('full_name'),
                            'gender' 		=> $request->get('gender'),
                            'dob' 			=> $request->get('dob'),
                            'pincode' 		=> $request->get('pincode'),
                            'address' 		=> $request->get('address'),
                            'state' 		=> $request->get('state'),
                            'city' 			=> $request->get('city'),
                            'user_type' 	=> $request->get('user_type'),
                            'dealer_id' 	=> $request->get('dealer_id'),
                            'referral_code' 	=> $request->get('referral_code'),
                            'profile_photo' => $safeName,
                         ]);
                } else {
                    $update_data = app_modal::where('id', $id)
                ->update([
                            'full_name' 	=> $request->get('full_name'),
                            'gender' 		=> $request->get('gender'),
                            'pincode' 		=> $request->get('pincode'),
                            'address' 		=> $request->get('address'),
                            'state' 		=> $request->get('state'),
                            'city' 			=> $request->get('city'),
                            'dob' 			=> $request->get('dob'),
                            'user_type' 	=> $request->get('user_type'),
                            'dealer_id' 	=> $request->get('dealer_id'),
                            'referral_code' 	=> $request->get('referral_code'),
                        ]);
                }

                if (!empty($request->get('user_type'))) {
                    $user_role = Roles::select('id')->where('name', $request->user_type)->first();
                    $role_user =RoleUser::where('user_id', $id)->update(['role_id'=>$user_role->id]);

                    if ($request->get('user_type')=='Dealer') {
                        $update_data = app_modal::where('id', $id)
                    ->update(['dealer_id'=>$id]);
                    }
                }

                $messgae = "User Updated Successfully";
            } else {
                $user_data = [];
                $storearray = [];
                $storearray['full_name'] 	= $request->full_name ??'';
                $storearray['gender']		= $request->gender ??'';
                $storearray['dob'] 			= $request->dob ??'';
                $storearray['email'] 		= $request->email ??'';
                $storearray['mobileno'] 	= $request->mobileno ??'';
                $storearray['pincode'] 		= $request->pincode ??'';
                $storearray['address'] 		= $request->address ??'';
                $storearray['state'] 		= $request->state ??'';
                $storearray['city'] 		= $request->city ??'';
                $storearray['password'] 	= Crypt::encrypt($request->get('password'));
                $storearray['user_type'] 	= $request->user_type;
                $storearray['referral_code'] 	= $request->referral_code;
                if (isset($safeName)) {
                    $storearray['profile_photo'] = $safeName ??'';
                }

                if ($request->get('user_type')!='Dealer') {
                    $storearray['dealer_id'] = $request->dealer_id;
                }
                $user_role = Roles::select('id')->where('name', $request->user_type)->first();


                $activate = $this->user_activation;




                $user = Sentinel::register($storearray, $activate);
                $role_user = new RoleUser();
                $role_user->user_id = $user->id;
                $role_user->role_id = $user_role->id;
                $role_user->privileges = null;
                $role_user->save();



                //Sentinel only save few fileds as per their configurations

                $recent_insert_update['gender']		= $request->gender ??'';
                $recent_insert_update['dob'] 		= $request->dob ??'';
                $recent_insert_update['pincode'] 	= $request->pincode ??'';
                $recent_insert_update['state'] 		= $request->state ??'';
                $recent_insert_update['city'] 		= $request->city ??'';
                $recent_insert_update['user_type'] 	= $request->user_type;
                if (isset($safeName)) {
                    $recent_insert_update['profile_photo'] = $safeName ??'';
                }

                $recent_insert_update = app_modal::where('id', $user->id)
                ->update($recent_insert_update);


                /* following code is not needed here
                if($request->get('user_type')=='Dealer')
                {
                    $update_data = app_modal::where('id', $user->id)
                    ->update(['dealer_id'=>$user->id]);
                }
                */
            }
        }

        if ($id != null) {
            $messgae = "User Updated Successfully";
            $output['status'] = 'success';
            $output['success_msg'] = $messgae;
            $output['msg'] = $messgae;
            $output['msgHead'] = "Success ! ";
            $output['msgType'] = "success";
            $output['success'] = true;
            $output['slideToTop'] = true;
            $output['url'] = route('admin.user.edit', ['bcat_id' => $id]);
            return response()->json($output);
        } else {
            $messgae = "User Created Successfully";
            $output['status'] = 'success';
            $output['success_msg'] = $messgae;
            $output['msg'] = $messgae;
            $output['msgHead'] = "Success ! ";
            $output['msgType'] = "success";
            $output['success'] = true;
            $output['slideToTop'] = true;
            $output['url'] = route('admin.user.edit', ['id' => $user->id]);
            return response()->json($output);
        }


        // $output['status'] = 'success';
        // $output['success_msg'] = $messgae;
        // $output['msg'] = $messgae;
        // $output['msgHead'] = "Success ! ";
        // $output['msgType'] = "success";
        // $output['success'] = true;
        // $output['slideToTop'] = true;
        // $output['url'] = $this->manager_url;
        // return response()->json($output);
    }

    public function show($ID)
    {
        $manager_name = $this->manager_name;
        $user = app_modal::select('*', DB::raw("DATE_FORMAT(created_at,'%d %M  %Y') as add_date"), DB::raw("DATE_FORMAT(updated_at,'%d %M  %Y') as update_date"))->where('id', $ID)->first();
        
        
        if (isset($user->dealer_id) && ($user->dealer_id)!='') {
            $dealerRecord = Dealer::select('dealer_name')->where('user_id', $user->dealer_id)->first();
            if(isset($dealerRecord)) $user->dealer_name = $dealerRecord->dealer_name; else $user->dealer_name = 'NA';
        }
        $user->bank_details = Bank_detail::where('user_id', $user->id)->first();
        $user->document_details = Document::where('user_id', $user->id)->first();
        $complete_bank_details = Bank_detail::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        $document_details = Document::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        $show_action = 1;
        return View('admin.' . $this->folder_name . '.show', compact('user', 'manager_name', 'complete_bank_details', 'document_details', 'show_action'));
    }

    public function getDistrictList($stateId)
    {
        $stateId = urldecode($stateId);
        // echo $stateId;die;
        $district = City::select('id', 'name')
        ->where("state_id", $stateId)
        ->get();
        $op['district'] = $district;
        return json_encode($op);
    }

    public function rewards(Request $request, $ID, $Type=null)
    {
        $manager_name = $this->manager_name;
        $user = app_modal::select('full_name')->where('id', $ID)->first();
        $points = CustomerPoints::select('customer_points.*');
        $points = $points->where('customer_points.user_id', $ID);
        if ($Type=='Earn') {
            $points = $points->where('customer_points.transaction_type', 'Earn');
        }
        if ($Type=='Redeem') {
            $points = $points->where('customer_points.transaction_type', 'Redeem');
        }
        if ($request->qr_value!='') {
            $points = $points->where('customer_points.qr_value', $request->qr_value);
        }
        if ($request->reward_points!='') {
            $points = $points->where('customer_points.point', $request->reward_points);
        }
        if ($request->transaction_type!='') {
            $points = $points->where('customer_points.transaction_type', $request->transaction_type);
        }
        $points = $points->orderBy('customer_points.id', 'desc')->get();
        //echo "<pre>";
        //print_r($points);
        //die();
        $pointBalance = CustomerPoints::getUserBalance($ID);
        return View('admin.' . $this->folder_name . '.rewards', compact('user', 'manager_name', 'points', 'pointBalance', 'ID'));
    }

    public function rewardAdd($USERID=null)
    {
        $manager_name = $this->manager_name;
        $refURL = explode('/', $_SERVER['HTTP_REFERER']);
        $redirect =  end($refURL);
        // dd($redirect);
        $data = null;
        $user = app_modal::select('full_name')->where('id', $USERID)->first();
        // dd($user);
        $saveURL = route('admin.user.reward.add', $USERID);
        // $prdData = Products::Select('*')->get();
        // return View('admin.' . $this->folder_name . '.rewardedit', compact('user', 'manager_name', 'data', 'saveURL', 'prdData'));
        return View('admin.' . $this->folder_name . '.rewardedit', compact('user', 'data', 'saveURL'));
    }

    public function rewardAddSave(Request $request, $USERID=null)
    {
        // echo "<pre>";print_r();die;
        $rules['transaction_type'] = 'required';
        $type1 = $request->transaction_type;
        if ($type1 == "Earn") {
            $rules['transaction_type'] = 'required';
            $rules['qr_value'] = 'required';
            $rules['point'] = 'required';
        } elseif ($type1 == "Redeem") {
            $rules['transaction_type'] = 'required';
            $rules['point'] = 'required';
        }

        $input = Input::all();

        $qrerror = '';
        $pointBalance = CustomerPoints::getUserBalance($USERID);
        if ($input['transaction_type']=='Earn') {
            #===Check if this QR Value not find in the product database=====#
            $prdData = Products::select('id', 'used_status', 'reward_points')->where('qr_value', $input['qr_value'])->first();
            if ($prdData) {
                if ($prdData->used_status == 'Yes') {
                    $qrerror = 'This QR Code is Already Used';
                } else {
                    if ($prdData->reward_points != $request->point) {
                        $qrerror = 'This Reward Point Value is not assigned to this Qr Code';
                    } else {
                        $prdData->used_status = "Yes";
                        $prdData->save();
                        $input['product_id'] = $prdData->id;
                    }
                }
            } else {
                $qrerror = 'Invalid Coupon Number';
            }
            // if(!isset($prdData)){
            // 	$qrerror = 	'Invalid QR Code';
            // }
            // else{
            // 	$input['product_id'] = $prdData->id;
            // }
        } else {
            #=====In Case of Redeem Points=========================#

            if (round($pointBalance['balance']) >= $request->point) {
                $input['coupon_no'] = null;
                $input['qr_value'] = null;
                $input['product_id'] = null;
            } else {
                $qrerror = 'You Have Only '.round($pointBalance['balance']).' Balance Point';
            }
        }

        $errorMsg = "Opps ! Some Error Occured. Please Try Again.";
        $validator = Validator::make($input, $rules);

        if (!empty($qrerror)) {
            $errorMsg = $errorMsg.'<br/>'.$qrerror;
        }
        if ($validator->fails() ||  $qrerror!='') {
            return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
        }
        #=========Query for insert=================================#
        #=====Update Current Points========#

        if ($input['transaction_type']=='Earn') {
            $currentPoints 	= $pointBalance['balance']+$input['point'];
            $description = 'Reward Point-QR Scan (Admin)';
        }
        if ($input['transaction_type']=='Redeem') {
            $currentPoints = $pointBalance['balance']-$input['point'];
            $description = 'Redeemed by Admin';
        }


        DB::table('customer_points')->insert(
            array(
                'point'            => $input['point'],
                'qr_value'         => $input['qr_value'],
                'product_id'       => $input['product_id'],
                'user_id'          => $USERID,
                'transaction_type' => $input['transaction_type'],
                'current_points'   => $currentPoints,
                'description'      => $description,
                'added_from'	   => 'admin',
            )
        );
	
		//============Check if User is applicable for bonus points if yes then award bonus points============#
			CustomerPoints::checkAwardBonusPoints($USERID, $currentPoints);
		//===================================================================================================#
     
        #============== Push Notification =========================#
        $user         =app_modal::select('id', 'full_name')->where('id', $USERID)->first();
        $notification ="Dear ".($user->full_name ?? 'User').",\n".$input['point']." Reward Points Added successfully to you wallet.";
        DB::table('notifications')->insert([
            'user_id'     => $user->id,
            'title'       => 'Reward Details',
            'description' => $notification,
            'type'        => Sentinel::findById($USERID)->roles[0]['slug'],
            'image'       => $input['transaction_type'].'.png',
            'status'      => 'Pending',
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s')
        ]);
        if ($type1 == "Earn") {
            $type = "Add Points";
            $point = $request->point;
        }
        if ($type1 == "Redeem") {
            $type = "Redeem Points";
            $point = $request->point;
        }
        $id = $USERID;
        $output = SendMessage::getSendMessage($type, $id, $point);
        #=========Response Return =============
        $messgae = 'Points Add Successfully';
        $output['status'] = 'success';
        $output['success_msg'] = $messgae;
        $output['msg'] = $messgae;
        $output['msgHead'] = "Success ! ";
        $output['msgType'] = "success";
        $output['success'] = true;
        $output['slideToTop'] = true;
        $output['url'] = route('user.rewards', [$USERID,$input['redirect']]);

        return response()->json($output);
    }

    public function rewardEdit($ID=null)
    {
        $manager_name = $this->manager_name;
        $refURL = explode('/', $_SERVER['HTTP_REFERER']);
        $redirect =  end($refURL);
        $data = CustomerPoints::select('*')->where('id', $ID)->first();
        $user = app_modal::select('full_name')->where('id', $data->user_id)->first();
        $saveURL = route('admin.user.reward.edit', $data->id);
        $prdData = Products::Select('*')->get();
        return View('admin.' . $this->folder_name . '.rewardedit', compact('user', 'manager_name', 'data', 'saveURL', 'prdData', 'redirect'));
    }

    public function rewardEditSave(Request $request, $ID=null)
    {
        $input = Input::all();
        $data = CustomerPoints::select('id', 'user_id', 'coupon_no', 'point')->where('id', $ID)->first();
        $rules['point'] = 'required';
        $rules['transaction_type'] = 'required';
        if ($input['transaction_type']=='Earn') {
            $rules['coupon_no'] = 'required|min:8|unique:customer_points,coupon_no,'.$ID;
            $rules['qr_value'] = 'required';
            #=========Check if QR Value is a part of Coupon Code===========#
            if (!str_contains($input['coupon_no'], $input['qr_value'])) {
                $qrerror = 	'QR Value is different from Coupon Number';
            }
            #===Check if this QR Value not find in the product database=====#
            $prdData = Products::select('id')->where('qr_value', $input['qr_value'])->first();
            if (!isset($prdData)) {
                $qrerror = 	'Invalid QR Code';
            } else {
                $input['product_id'] = $prdData->id;
            }
        } else {
            $input['coupon_no'] = null;
            $input['qr_value'] = null;
            $input['product_id'] = null;
        }
        $errorMsg = "Opps ! Some Error Occured. Please Try Again.";
        $validator = Validator::make($input, $rules);
        if (!empty($qrerror)) {
            $errorMsg = $qrerror;
        }
        if ($validator->fails() ||  $qrerror!='') {
            return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
        }
        $old_value=CustomerPoints::select('point', 'coupon_no', 'qr_value', 'transaction_type')->where('id', $ID)->first()->toArray();
        $new_value=[
            'point' => $input['point'],
            'coupon_no' => $input['coupon_no'],
            'qr_value' => $input['qr_value'],
            'transaction_type' => $input['transaction_type']
        ];
        $update=CustomerPoints::where('id', $ID)
        ->update($new_value);
        #============== Push Notification =====================
        $diff=array_diff($old_value, $new_value);
        if (count($diff)>0) {
            $user         =app_modal::select('id', 'full_name')->where('id', CustomerPoints::where('id', $ID)->first()->user_id)->first();
            $notification ="Dear ".($user->full_name ?? 'User')."\n Your reward point transaction has been updated, please check your wallet.";
            DB::table('notifications')->insert([
                'user_id'     => $user->id,
                'title'       => 'Reward Details',
                'description' => $notification,
                'type'        => Sentinel::findById($user->id)->roles[0]['slug'],
                'image'       => $input['transaction_type'].'.png',
                'status'      => 'Pending',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s')
            ]);
        }
        #=========Query for insert======
        $messgae = 'Points Updated Successfully';
        $output['status'] = 'success';
        $output['success_msg'] = $messgae;
        $output['msg'] = $messgae;
        $output['msgHead'] = "Success ! ";
        $output['msgType'] = "success";
        $output['success'] = true;
        $output['slideToTop'] = true;
        $output['url'] = route('user.rewards', [$data->user_id,$input['redirect']]);
        return response()->json($output);
    }

    public function couponcheck(Request $request)
    {
        $inputs = $request->all();
        $coupon_no = $inputs['coupon_no'];
        // $qr_value = substr_replace($coupon_no ,"", -6);
        $prdData = Products::select('reward_points', 'used_status')->where('qr_value', $coupon_no)->first();
        if ($prdData) {
            if ($prdData->used_status == 'Yes') {
                $output['key_value'] = 2;
                $output['error'] = 'This QR Code is Already Used';
            } else {
                $output['key_value'] = 1;
                $output['qr_value']			= $coupon_no;
                $output['reward_points']	= $prdData->reward_points;
            }
        } else {
            $output['key_value'] = 0;
            $output['error'] = 'Invalid Coupon Number';
        }
        return response()->json($output);
    }

    public function getModalApprovePending($id = null)
    {
        $model = 'User';
        $confirm_route = $error = null;
        $user = app_modal::where('id', $id)->first();
        if (empty($user)) {
            return Redirect::route('info');
        } else {
            if ($user->profile_status=='Approve') {
                $type           ='Pending';
            } else {
                $type           ='Approve';
            }
            $confirm_route  = route('approve-pending', ['id' => $id]);
            return View('admin/layouts/status_modal_confirmation', compact('error', 'model', 'confirm_route', 'type'));
        }
    }

	public function approve_pending($id = null)
    {
        $user = app_modal::where('id', $id)->first();
        if(!empty($user))
        {
            if ($user->profile_status=='Approve')
            {
                $type1='Pending';
                $type='Profile Pending';
            }
            else
            {
                $type1='Approve';
                $type='Profile Approve';
            }

            app_modal::where('id', $id)->update(['profile_status' => $type1]);

            if ($type1=='Approve' && $user->DeviceToken!="0")
            {
                #========Update Free Signup Bonus==========================#
                $signupBonus = WebsiteSetting::where('id', 1)->first()->signup_bonus;
                if($signupBonus > 0)
                {
                    $pointBalance = CustomerPoints::getUserBalance($id);
                    $input['qr_value'] = null;
                    $input['product_id'] = null;
                    $currentPoints =      $pointBalance['balance'] + $signupBonus;

                    DB::table('customer_points')->insert(
                        array(
                            'point'            => $signupBonus,
                            'qr_value'         => $input['qr_value'],
                            'product_id'       => $input['product_id'],
                            'coupon_no'        => 'Signup Bonus',
                            'user_id'          => $id,
                            'transaction_type' => 'Earn',
                            'current_points'   => $currentPoints,
                            'added_from'	   => 'admin',
                            'description'	   => 'Reward Point-Signup Bonus',
                        )
                    );
                }

                #=====Send Push Notification Real Time Update==================#

                $deviceToken[] = $user->DeviceToken;
                $msg1 =  array();
                $msg1['title'] = "Profile Status";
                $msg1['description'] = "Your Profile has been Approved.";
                $msg1 = (object)$msg1;
                PushNotification::send($msg1, $deviceToken, "profile");
            }
        }

        $output = SendMessage::getSendMessage($type, $id);
        $message = "User ".$type1." Successfully";
        $notification = array(
            'message' => $message,
            'alert-type' => 'success',
        );
        return redirect('cpmin/user')->with($notification);
    }

    public function getModalApprovereject($id = null)
    {
        $model = 'User';
        $confirm_route = $error = null;
        $user = app_modal::where('id', $id)->first();
        if (empty($user)) {
            return Redirect::route('info');
        } else {
            $type = 'Reject';
            $confirm_route  = route('approve-reject', ['id' => $id]);
            return View('admin/layouts/status_modal_confirmation', compact('error', 'model', 'confirm_route', 'type'));
        }
    }

    public function approve_reject($id = null)
    {
        $user = app_modal::where('id', $id)->first();
        if(!empty($user))
        {
            $type1='Reject';
            $type='Profile Reject';

            app_modal::where('id', $id)->update(['profile_status' => $type1]);

            if ($type1=='Reject' && $user->DeviceToken!="0")
            {
                #=====Send Push Notification Real Time Update==================#

                $deviceToken[] = $user->DeviceToken;
                $msg1 =  array();
                $msg1['title'] = "Profile Status";
                $msg1['description'] = "Your Profile has been Rejected.";
                $msg1 = (object)$msg1;
                PushNotification::send($msg1, $deviceToken, "profile");
            }

        }
        $output = SendMessage::getSendMessage($type, $id);
        $message = "User ".$type1." Successfully";
        $notification = array(
            'message' => $message,
            'alert-type' => 'success',
        );
        return redirect('cpmin/user')->with($notification);
    }

    public function getModalApprovestatus($id = null)
    {
        $model = 'User';
        $confirm_route = $error = null;
        $user = app_modal::where('id', $id)->first();
        if (empty($user)) {
            return Redirect::route('info');
        } else {
            if ($user->status == "Active") {
                $type='Block';
            }
            else{
                $type='UnBlock';
            }
            $confirm_route  = route('approve-status', ['id' => $id]);
            return View('admin/layouts/status_modal_confirmation', compact('error', 'model', 'confirm_route', 'type'));
        }
    }

    public function approve_status($id = null)
    {
        $user = app_modal::where('id', $id)->first();
        if(!empty($user))
        {
            if ($user->status == "Active") {
                $type1='Inactive';
                $type='Profile Blocked';
            }
            else{
                $type1='Active';
                $type='Profile UnBlock';
            }

            app_modal::where('id', $id)->update(['status' => $type1]);

            // if ($type1=='Reject' && $user->DeviceToken!="0")
            // {
            //     #=====Send Push Notification Real Time Update==================#

            //     $deviceToken[] = $user->DeviceToken;
            //     $msg1 =  array();
            //     $msg1['title'] = "Profile Status";
            //     $msg1['description'] = "Your Profile has been Rejected.";
            //     $msg1 = (object)$msg1;
            //     PushNotification::send($msg1, $deviceToken, "ProfileStatus");
            // }

        }

        $output = SendMessage::getSendMessage($type, $id);
        $message = "User ".$type1." Successfully";
        $notification = array(
            'message' => $message,
            'alert-type' => 'success',
        );
        return redirect('cpmin/user/approved-customers')->with($notification);
    }

    public function getRewardModalDelete($id = null)
    {
        //echo "getModal"; die;
        $model = 'Rewards';
        $confirm_route = $error = null;
        $point = CustomerPoints::where('id', $id)->first();
        if (empty($point)) {
            return Redirect::route('info');
        } else {
            $confirm_route = route('admin.reward.delete', ['id' => $id]);
            return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    public function getRewardDelete($id)
    {
        $refURL = explode('/', $_SERVER['HTTP_REFERER']);
        $redirect =  end($refURL);
        $point = CustomerPoints::where('id', $id)->first();
        #============== Push Notification =====================
        $user         =app_modal::select('id', 'full_name')->where('id', $point->user_id)->first();
        $notification ="Dear ".($user->full_name ?? 'User').",\n".$point->point." Reward Points has been deleted, Please check your wallet.";
        DB::table('notifications')->insert([
            'user_id'     => $user->id,
            'title'       => 'Reward Details',
            'description' => $notification,
            'type'        => Sentinel::findById($user->id)->roles[0]['slug'],
            'status'      => 'Pending',
            'image'       => 'Redeem.png',
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s')
        ]);
        $point->forceDelete();
        $message = "Points Deleted permanently Successfully";
        $notification = array(
            'message' => $message,
            'alert-type' => 'success',
        );
        return redirect('admin/user/rewards/'.$point->user_id.'/'.$redirect)->with($notification);
    }

    public function getModalDelete($id = null)
    {
        $model = $this->manager_name;
        $confirm_route = $error = null;
        $confirm_route = route('delete/admin_user', ['id' => $id]);
        return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
    }

    public function destroy($id = null)
    {
        $detail = app_modal::where('id', $id)->first();
        if (isset($detail->profile_photo)) {
            $folderName = '/' . $this->folder_name;
            $filedir = $folderName . '/' . $detail->profile_photo;
            Storage::disk('uploads')->delete($filedir);
        }
        $type = "Profile Deleted";
        $output = SendMessage::getSendMessage($type, $id);
        app_modal::where('id', $id)->delete();
      
        $success = $this->manager_name . " Deleted Succesfully";
        return redirect($this->manager_url)->with('success', $success);
    }

    public function getModalDeleteDocument($id = null)
    {
        $model = $this->manager_name;
        $confirm_route = $error = null;
        $confirm_route = route('delete-document/admin_user', ['id' => $id]);
        return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
    }

    public function destroydocument($id = null)
    {
        $detail = app_modal::where('id', $id)->first();
        if (isset($detail->profile_photo)) {
            $folderName = '/' . $this->folder_name;
            $filedir = $folderName . '/' . $detail->profile_photo;
            Storage::disk('uploads')->delete($filedir);
        }
        Document::where('id', $id)->delete();
        $success = $this->manager_name . " Document Deleted Succesfully";
        return redirect($this->manager_url)->with('success', $success);
    }

    public function destroybankdetails($id = null)
    {
        $model = $this->manager_name;
        $confirm_route = $error = null;
        $confirm_route = route('confirm-delete-bank-detail/admin_user', ['id' => $id]);
        return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
    }

    public function getModalDeleteBankdetail($id = null)
    {
        $detail = app_modal::where('id', $id)->first();
        if (isset($detail->profile_photo)) {
            $folderName = '/' . $this->folder_name;
            $filedir = $folderName . '/' . $detail->profile_photo;
            Storage::disk('uploads')->delete($filedir);
        }
        Bank_detail::where('id', $id)->delete();
        $success = $this->manager_name . " Bank Details Deleted Succesfully";
        return redirect($this->manager_url)->with('success', $success);
    }
    
    public function trashedUsers()
    {
        //echo "done";die;
        $trashedUser = app_modal::select('users.*')
        // ->join('role_users', 'role_users.user_id', '=', 'users.id')
        // ->join('roles', 'roles.id', '=', 'role_users.role_id')
        // ->where('roles.name', 'User')
        ->onlyTrashed()
        ->get();
        // dd($trashedUser);
        $PARENT_ID = 51;
        return view('admin.user.deleted-user-list', compact('PARENT_ID', 'trashedUser'));
    }

    public function getModalPermanentDelete($id = null)
    {
        //echo "getModal"; die;
        $model = 'User';
        $confirm_route = $error = null;
        $user = app_modal::where('id', $id)->onlyTrashed()->first();
        if (empty($user)) {
            return Redirect::route('info');
        } else {
            $confirm_route = route('deleted-permanent/user', ['id' => $id]);
            return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    public function destroyPermanent($id)
    {
        $user = app_modal::where('id', $id)->onlyTrashed()->first();
        //	$userProfile= UserProfile::wher
    }

    public function stepUpdate(Request $request, $id){
        $model = 'Update Step';
        $type = 'Update';
        $confirm_route = $error = null;
        $point = User::where('id', $id)->first();
        if (empty($point)) {
            return Redirect::route('info');
        } else {
            $confirm_route = route('user.step-update-success');
            return View('admin/layouts/step_modal_conformtion', compact('error', 'model', 'confirm_route','type','id'));
        }
    }

    public function stepUpdateSuccess(Request $request){
        $id = $request->id;
        $step = $request->step;
        $data = User::where('id',$id)->first();
        $data->step_completed = $step;
        $data->save();
        $messgae = 'Step Updated Successfully';
        $output['status'] = 'success';
        $output['success_msg'] = $messgae;
        $output['msg'] = $messgae;
        $output['msgHead'] = "Success ! ";
        $output['msgType'] = "success";
        $output['success'] = true;
        $output['slideToTop'] = true;
        $output['url'] = route('admin.pending_customer');
        return response()->json($output);
    }

}