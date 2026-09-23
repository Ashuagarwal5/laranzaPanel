<?php
namespace App\Http\Controllers\Admin;
use App\Helpers\datehelper;
use App\Testimonial;
use App\WebsiteSetting;
use Datatables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Redirect;
use Validator;
use View;

class TestimonialAdminController extends Controller {
	function __construct() {
		$this->date = datehelper::dateformat();
		$this->manager_name = 'Testimonials';
		$this->folder_name = 'testimonial';
		// Testimonial    = 'App\Testimonial';
		$this->manager_url = route('admin.testimonial');
		$this->route_create = route('create.testimonial');
		$this->route_data = route('admin.testimonial.data');
		$this->update_post = 'updated.testimonial';
		$this->store_post = 'store.testimonial';
		$this->destroy_url = 'delete.testimonial';
		$this->date_format = WebsiteSetting::select('dateformat')->first();
	}
	//================== Admin   View Function ==================//
	public function index() {
		$manager_name = $this->manager_name;
		$PARENT_ID = 113;
		$route_create = $this->route_create;
		$route_data = $this->route_data;
		$folder_name = $this->folder_name;
		return view('admin.' . $this->folder_name . '.list', compact('PARENT_ID', 'folder_name', 'manager_name', 'route_create', 'route_data'));
	}
	public function data() {
		$date_format = $this->date_format->dateformat;
		$data = Testimonial::select(['*', DB::raw("DATE_FORMAT(created_at,'$date_format') as add_date")])
			->orderBy('id', 'desc')->get();
		return Datatables::of($data)
			->addColumn('actions', '<div class="btn-group">
			@if($status == "Active")
			<a title="Inactive Record"   class="btn btn-success enable-tooltip" href="{{URL::to("admin/testimonial/doTask/Inactive/$id")}}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-circle-o"></i></a>
			@else
			<a title="Active Record"  class="btn btn-warning enable-tooltip" href="{{URL::to("admin/testimonial/doTask/Active/$id")}}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-circle"></i></a>
			@endif
			<a class="btn btn-default" href="{{URL::to("admin/testimonial/show/$id")}}" title="View" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-eye"></i> </a>
			<a class="btn btn-primary" href="{{URL::to("admin/testimonial/edit/$id")}}" title="Edit"><i class="fa fa-edit"></i> </a>
			<a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/testimonial/$id/confirm-delete")}}" title="Delete"><i class="fa fa-trash"></i> </a>
			</div>
			')
			->rawColumns(['actions'])
			->make(true);
	}
	public function doTask(Request $request, $task, $ID) {
		$manager_name = $this->manager_name;
		$date_format = $this->date_format->dateformat;
		$detail = Testimonial::Select('*', DB::raw("DATE_FORMAT(created_at,'$date_format') as add_date"), DB::raw("DATE_FORMAT(updated_at,'$date_format') as update_date"))->where('id', $ID)->first();
		$redirect_url = $_SERVER['HTTP_REFERER'];
		$taskP = $task = ucfirst($task);
		$navi['route'] = route('testimonial.doTask', ['task' => $taskP, 'id' => $ID]);
		if (!empty($_POST)) {
			if ($request->get('confirm') == 'yes') {
				Testimonial::where('id', $ID)->update(['status' => $task]);
				$success = 'Status of Selected Record Changed successfully.';
				return redirect($this->manager_url)->with('success', $success);
			}
		}
		return View('admin/layouts/active_inactive_view', compact('navi', 'detail', 'taskP', 'manager_name'));
	}
	public function create($ID = NULL) {
		$manager_name = $this->manager_name;
		$manager_url = $this->manager_url;
		$PARENT_ID = 113;
		if ($ID) {
			$route = route($this->update_post, ['id' => $ID]);
			$data = Testimonial::find($ID);
			if (count($data) == 0) {
				$messgae = "This is not valid action, data not found.";
				return redirect($this->manager_url)->with('error', trans($messgae));
			}
			$data = Testimonial::Select('*')->where('id', $ID)->first();
			return View('admin.' . $this->folder_name . '.create', compact('data', 'route', 'PARENT_ID', 'manager_name', 'manager_url'));
		}
		$route = route($this->store_post);
		return View('admin.' . $this->folder_name . '.create', compact('route', 'PARENT_ID', 'manager_name', 'manager_url'));
	}
	public function store(Request $request, $ID = NULL) {
		$input = Input::all();
		$rules['client_name'] = 'required';
		// $rules['client_contactno'] ='required';
		// $rules['client_email'] ='required';
		$rules['client_location'] = 'required';
		$rules['feedback'] = 'required';
		$errorMsg = "Opps ! Some Error Occured. Please Try Again.";
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
		}
		$records = Testimonial::Select('*')->where('id', $ID)->first();
		$input = $request->all();
		if ($file = $request->file('client_photo')) {
			$fileName = $file->getClientOriginalName();
			$extension = $file->getClientOriginalExtension();
			$folderName = '/feedback';
			$safeName = str_random(10) . '.' . $extension;
			@mkdir($folderName, 0777, true);
			@chmod($folderName, 0777);
			Storage::disk('uploads')->putFileAs($folderName, $file, $safeName);
			$input['client_photo'] = $safeName;
			if (count($records) > 0 && ($records->client_photo != '') && ($input['client_photo'] != '')) {
				$folderName = '/client_photo';
				$filedir = $folderName . '/' . $records->client_photo;
				Storage::disk('uploads')->delete($filedir);
			}
		} else {
			if (isset($records)) {
				$input['client_photo'] = $records->client_photo;
			}
		}
		if ($ID != '') {
			$data = Testimonial::find($ID);
			if (count($data) == 0) {
				$messgae = "This is not valid action, data not found.";
				return redirect($this->manager_url)->with('error', trans($messgae));
			}
		}
		$tournament = Testimonial::updateOrCreate(['id' => $ID], $input);
		if ($ID != NULL) {
			$messge = $this->manager_name . " Updated Successfully";
		} else {
			$messge = $this->manager_name . " Created Successfully";
		}
		if ($tournament->save()) {
			$output['status'] = 'success';
			$output['msg'] = $messge;
			$output['success_msg'] = $messge;
			$output['msgHead'] = "Success ! ";
			$output['msgType'] = "success";
			$output['resetform'] = true;
			$output['success'] = true;
			$output['slideToTop'] = true;
			$output['url'] = $this->manager_url;
			return response()->json($output);
		} else {
			return redirect($this->manager_url . '/create')->with('error', 'Oops! Something went wrong.');
		}
	}
	public function show($ID) {
		$manager_name = $this->manager_name;
		$date_format = $this->date_format->dateformat;
		$detail = Testimonial::Select('*', DB::raw("DATE_FORMAT(created_at,'$date_format') as add_date"), DB::raw("DATE_FORMAT(updated_at,'$date_format') as update_date"))->where('id', $ID)->first();
		//echo  "<pre>"; print_r($detail); die;
		return View('admin.' . $this->folder_name . '.show', compact('detail', 'manager_name'));
	}
	public function getModalDelete($id = NULL) {
		$model = $this->manager_name;
		$confirm_route = $error = null;
		$confirm_route = route($this->destroy_url, ['id' => $id]);
		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
	}
	public function destroy($id = NULL) {
		$detail = Testimonial::where('id', $id)->first();
		if (isset($detail->image)) {
			$folderName = '/' . $this->folder_name;
			$filedir = $folderName . '/' . $detail->image;
			Storage::disk('uploads')->delete($filedir);
		}
		Testimonial::where('id', $id)->withTrashed()->forceDelete();
		$success = $this->manager_name . " Deleted Succesfully";
		return redirect($this->manager_url)->with('success', $success);
	}
}