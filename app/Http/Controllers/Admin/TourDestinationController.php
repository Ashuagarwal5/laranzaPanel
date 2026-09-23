<?php
namespace App\Http\Controllers\Admin;
use App\Helpers\datehelper;
use App\TourDestination as app_modal;
use App\TourDestinationImage;
use Datatables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Redirect;
use Validator;
use View;
class TourDestinationController extends Controller {
	function __construct() {
		$this->date = datehelper::dateformat();
		$this->manager_name = 'Tour Destination';
		$this->folder_name = 'tour-destination';
		//  $this->app_modal    = 'App\Testimonial';
		$this->manager_url = route('admin.tour-destination');
		$this->route_create = route('create.tour-destination');
		$this->route_data = route('admin.tour-destination.data');
		$this->update_post = 'updated.tour-destination';
		$this->store_post = 'store.tour-destination';
		$this->destroy_url = 'delete.tour-destination';
	}
	//================== Admin   View Function ==================//
	public function index() {
		$manager_name = $this->manager_name;
		$PARENT_ID = 139;
		$route_create = $this->route_create;
		$route_data = $this->route_data;
		$folder_name = $this->folder_name;
		return view('admin.' . $this->folder_name . '.list', compact('PARENT_ID', 'folder_name', 'manager_name', 'route_create', 'route_data'));
	}
	public function data() {
		$data = app_modal::select('*')
		->orderBy('id', 'desc')->get();
		return Datatables::of($data)
		->addColumn('actions', '<div class="btn-group">
			<a class="btn btn-primary" href="{{URL::to("securekhcpmin/tour-destination/edit/$id")}}" title="Edit"><i class="fa fa-edit"></i> </a>
			<a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="{{URL::to("securekhcpmin/tour-destination/$id/confirm-delete")}}" title="Delete"><i class="fa fa-trash"></i> </a>
			<a class="btn btn-success" href="{{route("add_images.tour-destination",$id)}}" title="Edit"><i class="fa fa-plus"></i> </a>
			</div>
			')
		->rawColumns(['actions'])
		->make(true);
	}
	public function create($ID = NULL) {
		$manager_name = $this->manager_name;
		$manager_url = $this->manager_url;
		$PARENT_ID = 139;
		if ($ID) {
			$route = route($this->update_post, ['id' => $ID]);
			$data = app_modal::find($ID);
			if (count($data) == 0) {
				$messgae = "This is not valid action, data not found.";
				return redirect($this->manager_url)->with('error', trans($messgae));
			}
			$data = app_modal::Select('*')->where('id', $ID)->first();
			return View('admin.' . $this->folder_name . '.create', compact('data', 'route', 'PARENT_ID', 'manager_name', 'manager_url'));
		}
		$route = route($this->store_post);
		return View('admin.' . $this->folder_name . '.create', compact('route', 'PARENT_ID', 'manager_name', 'manager_url'));
	}
	public function add_images($ID) {
		$manager_name = $this->manager_name;
		$manager_url = $this->manager_url;
		$PARENT_ID = 139;
		$tour_destination_id = $ID;

		$images = TourDestinationImage::select('id','image')->where('tour_destination_id',$ID)->get();
		return View('admin.' . $this->folder_name . '.add_images', compact('route', 'PARENT_ID', 'manager_name', 'manager_url','tour_destination_id','images'));
	}
	public function store_images(Request $request, $ID)
	{
		$input = $request->all();
		$rules['image'] = 'required';
		$errorMsg = "Opps ! Some Error Occured. Please Try Again.";
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
		}
		else
		{
			if ($files = $request->file('image')) {
				foreach ($files as $key => $value) {
					$fileName = $value->getClientOriginalName();
					$extension = $value->getClientOriginalExtension();
					$folderName = '/' . $this->folder_name.'/'.$ID;
					$safeName = str_random(10) . '.' . $extension;
					@mkdir($folderName, 0777, true);
					@chmod($folderName, 0777);
					Storage::disk('uploads')->putFileAs($folderName, $value, $safeName);
					$input['image'] = $safeName;
					$images = new TourDestinationImage();
					$images->image = $input['image'];
					$images->tour_destination_id = $ID;
					$images->save();
				}
			}
			$output['status'] = 'success';
			$output['step'] = 'step1';
			$output['success'] = true;
			$output['slideToTop'] = true;
			$output['success_msg'] = $messgae;
			$output['url'] = route('add_images.tour-destination',['id'=>$ID]);;
			echo json_encode($output);
		}
	}
	public function delete_image($id,$id2)
	{
		$detail = TourDestinationImage::where('id', $id)->first();
		if (isset($detail->image)) {
			$folderName = '/' . $this->folder_name.'/'.$id;
			$filedir = $folderName . '/' . $detail->image;
			Storage::disk('uploads')->delete($filedir);
		}
		TourDestinationImage::where('tour_destination_id',$id)->where('id', $id2)->forceDelete();
		$success = $this->manager_name . " Deleted Succesfully";
		$route = route('add_images.tour-destination',['id'=>$id]);
		return redirect($route)->with('success', $success);
	}
	public function store(Request $request, $ID = NULL) {
		$input = Input::all();
		//$rules['destination_type'] ='required';
		if ($ID == null) {
			$rules['destination_name'] = 'required|unique:tour_destination,destination_name';
			$rules['latitude'] = 'required';
			$c_message = [
				'latitude.required' => 'Please Select Your Location.',
			];
		} else {
			$rules['destination_name'] = 'required|unique:tour_destination,destination_name,' . $ID;
			$rules['latitude'] = 'required';
			$c_message = [
				'latitude.required' => 'Please Select Your Location.',
			];
		}
		if (!isset($ID)) {
			$rules['image'] = 'required|mimes:jpg,jpeg,png|max:1024';
		}
		$errorMsg = "Opps ! Some Error Occured. Please Try Again.";
		$validator = Validator::make($input, $rules, $c_message);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
		}
		$records = app_modal::Select('*')->where('id', $ID)->first();
		$input = $request->all();
		// echo "<pre>";
		// print_r($input);
		// die;
		if ($file = $request->file('image')) {
			$fileName = $file->getClientOriginalName();
			$extension = $file->getClientOriginalExtension();
			$folderName = '/' . $this->folder_name;
			$safeName = str_random(10) . '.' . $extension;
			@mkdir($folderName, 0777, true);
			@chmod($folderName, 0777);
			Storage::disk('uploads')->putFileAs($folderName, $file, $safeName);
			$input['image'] = $safeName;
			if (count($records) > 0 && ($records->image != '') && ($input['image'] != '')) {
				$folderName = '/' . $this->folder_name;
				$filedir = $folderName . '/' . $records->image;
				Storage::disk('uploads')->delete($filedir);
			}
		} else {
			$input['image'] = $records->image;
		}
		//    if ($file = $request->file('client_photo')){
		//        $fileName = $file->getClientOriginalName();
		//        $extension = $file->getClientOriginalExtension();
		//       $folderName = '/client_photo';
		//        $safeName = str_random(10) . '.' . $extension;
		//         @mkdir($folderName,0777,true);
		// 		@chmod($folderName,0777);
		//       Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
		//   $input['client_photo'] = $safeName;
		//
		// 	 if(count($records)>0 && ($records->client_photo!='') && ($input['client_photo']!=''))
		//    {
		// 		$folderName = '/client_photo';
		// 		$filedir = $folderName .'/'. $records->client_photo;
		//   Storage::disk('uploads')->delete($filedir);
		//
		//  }
		//
		// 	}
		//  else
		// 	{
		// 	$input['client_photo'] = $records->client_photo;
		// }
		if ($ID != '') {
			$data = app_modal::find($ID);
			if (count($data) == 0) {
				$messgae = "This is not valid action, data not found.";
				return redirect($this->manager_url)->with('error', trans($messgae));
			}
		}
		$tournament = app_modal::updateOrCreate(['id' => $ID], $input);
		if ($ID != NULL) {
			$messgae = $this->manager_name . " Updated Successfully";
		} else {
			$messgae = $this->manager_name . " Created Successfully";
		}
		if ($tournament->save()) {
			//app_modal::where('id', $tournament->id);
			$output['status'] = 'success';
			$output['step'] = 'step1';
			$output['success'] = true;
			$output['slideToTop'] = true;
			$output['success_msg'] = $messgae;
			$output['url'] = $this->manager_url;
			echo json_encode($output);
		} else {
			return redirect($this->manager_url . '/create')->with('error', 'Oops! Something went wrong.');
		}
	}
	public function show($ID) {
		$manager_name = $this->manager_name;
		$detail = app_modal::Select('*', DB::raw("DATE_FORMAT(created_at,'%d %M  %Y') as add_date"), DB::raw("DATE_FORMAT(updated_at,'%d %M  %Y') as update_date"))->where('id', $ID)->first();
		//echo  "<pre>"; print_r($detail);
		return View('admin.' . $this->folder_name . '.show', compact('detail', 'manager_name'));
	}
	public function getModalDelete($id = NULL) {
		$model = $this->manager_name;
		$confirm_route = $error = null;
		$confirm_route = route($this->destroy_url, ['id' => $id]);
		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
	}
	public function destroy($id = NULL) {
		$detail = app_modal::where('id', $id)->first();
		if (isset($detail->image)) {
			$folderName = '/' . $this->folder_name;
			$filedir = $folderName . '/' . $detail->image;
			Storage::disk('uploads')->delete($filedir);
		}
		app_modal::where('id', $id)->delete();
		$success = $this->manager_name . " Deleted Succesfully";
		return redirect($this->manager_url)->with('success', $success);
	}
	//========================================Origin City Trash====================================================
	public function listDeletedtrash() {
		$request_url_user = 'OriginCity';
		$PARENT_ID = 156;
		return view('admin.origncity_trash.deletedlist', compact('PARENT_ID', 'request_url_user'));
	}
	public function listDeletedData() {
		$enquiry = app_modal::select(['id','destination_type', 'destination_name', 'short_description', DB::raw("DATE_FORMAT(`tbl_tour_destination`.created_at,'%d %M  %Y') as add_date")])
		->orderBy('id', 'desc')
		->onlyTrashed()
		->get();
		// print_r($enquiry);die;
		return Datatables::of($enquiry)
		->addColumn('actions', '<div class="btn-group">
			<a data-toggle="modal" data-target="#modal-large" href="{{URL::to("securekhcpmin/origncity_trash/$id/confirm-restore")}}" class="btn btn-small btn-default"title="Restore"><i class="fa fa-undo"> Restore</i></a>
			<a data-toggle="modal" data-target="#modal-large" href="{{URL::to("securekhcpmin/origncity_trash/$id/final-delete")}}" class="delval btn btn-small btn-danger" title="Permanent Delete"><i class="fa fa-trash"> Delete</i></a></div>
			')->rawColumns(['actions'])->make(true);
	}
	public function getModalRestore($id = null) {
		$model = 'Origin City Trash Record';
		$confirm_route = $error = null;
		$confirm_route = route('restore/origncity_trash', ['id' => $id]);
		return View('admin/layouts/restore_modal_confirmation', compact('error', 'model', 'confirm_route'));
	}
	public function restoreDeletedtrash($enu_id) {
		app_modal::withTrashed()->where('id', $enu_id)->restore();
		$success = "Trash Record Restore Successfully";
		return Redirect::route('origncity-trash/deletedfeedback')->with('success', $success);
	}
	public function getModalFinalDelete($id = null) {
		$model = 'Origin City Trash Record';
		$confirm_route = $error = null;
		$confirm_route = route('finaldelete/origncity_trash', ['id' => $id]);
		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
	}
	public function permanentDelete($id) {
		$detail = app_modal::select('*')->where('id', $id)->onlyTrashed()->first();
		if (isset($detail->image)) {
			$folderName = '/tour-destination';
			$filedir = $folderName . '/' . $detail->profile_photo;
			$query = Storage::disk('uploads')->delete($filedir);
			// echo($filedir);die;
		}
		app_modal::where('id', $id)->withTrashed()->forceDelete();
		$success = "Trash Record Permanently Deleted Successfully";
		return Redirect::route('origncity-trash/deletedfeedback')->with('success', $success);
	}
}