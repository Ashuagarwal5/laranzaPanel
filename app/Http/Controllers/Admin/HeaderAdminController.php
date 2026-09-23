<?php
namespace App\Http\Controllers\Admin;
use App\HeaderMenu;
use App\Helpers\datehelper;
use Datatables;
use DB;
use Illuminate\Support\Facades\Input;
use Redirect;
use Validator;
use View;

class HeaderAdminController extends Controller {
	function __construct() {
		$this->date = datehelper::dateformat();
	}
	//=================================================================//
	//================== Admin   View Function ==================//
	public function index() {
		$PARENT_ID = 103;
		return view('admin.headermenu.list', compact('PARENT_ID'));
	}
	public function data() {
		$data = HeaderMenu::select(['hd_id', 'link_showing_name', 'shownig_order', 'page_set', 'link_address', 'created_at', 'parent_menu'
			, DB::raw("DATE_FORMAT(created_at,'%d %M  %Y') as add_date")])
			->orderBy('hd_id', 'desc')
			->where('parent_menu', 0)
			->get();
		return Datatables::of($data)
			->addColumn('actions', '<div class="btn-group">
			@if($parent_menu==50000000000000)
			<a class="btn btn-primary" href="{{URL::to("admin/header/createmain/$hd_id")}}" title="Edit"><i class="fa fa-edit"></i> Create Sub Menu</a>
			@endif
			<a class="btn btn-primary" href="{{URL::to("admin/header/edit/$hd_id")}}" title="Edit"><i class="fa fa-edit"></i> Edit</a>&nbsp;
			<a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/header/$hd_id/confirm-delete")}}" title="Delete"><i class="fa fa-trash"></i> Delete</a>
			</div>
			')
			->rawColumns(['actions'])
			->make(true);
	}
	public function subdata() {
		// $parent_menu_name = HeaderMenu::select(['link_showing_name'
		// ,DB::raw("DATE_FORMAT(created_at,'%d %M  %Y') as add_date")] )
		// ->orderBy('hd_id', 'desc')
		// ->where('parent_menu','hd_id')
		// ->get();
		// echo "<pre>";
		// print_r($parent_menu_name);
		$data = HeaderMenu::select(['hd_id', 'link_showing_name', 'shownig_order', 'page_set', 'link_address', 'created_at', 'parent_menu'
			, DB::raw("DATE_FORMAT(created_at,'%d %M  %Y') as add_date")])
			->orderBy('hd_id', 'desc')
			->where('parent_menu', '!=', 0)
			->get();
		return Datatables::of($data)
			->addColumn('actions', '<div class="btn-group">
			<a class="btn btn-primary" href="{{URL::to("admin/header/edit/$hd_id")}}" title="Edit"><i class="fa fa-edit"></i> Edit</a>&nbsp;
			<a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/header/$hd_id/confirm-delete")}}" title="Delete"><i class="fa fa-trash"></i> Delete</a>
			</div>
			')
			->rawColumns(['actions'])
			->make(true);
	}
	public function submenu($ID = NULL) {
		$PARENT_ID = 103;
		return view('admin.headermenu.sublist', compact('PARENT_ID'));
	}
	public function createmain($ID = NULL) {
		$type = "main";
		$PARENT_ID = 103;
		$sub_id = $ID;
		$route = route('store.header', ['type' => $type]);
		return View('admin.headermenu.subcreate', compact('route', 'type', 'PARENT_ID', 'sub_id'));
	}
	public function create($ID = NULL) {
		$PARENT_ID = 103;
		$type = "main";
		if ($ID) {
			$route = route('updated.header', ['ID' => $ID, 'type' => $type]);
			$data = HeaderMenu::find($ID);
			if (count((array) $data) == 0) {
				$messgae = "This is not valid action, data not found.";
				return redirect('admin.header')->with('error', trans($messgae));
			}
			$data = HeaderMenu::Select('*')->where('hd_id', $ID)->first();
			return View('admin.headermenu.create', compact('data', 'route', 'type', 'PARENT_ID'));
		}

		$route = route('store.header', ['type' => $type]);
		return View('admin.headermenu.create', compact('route', 'type', 'PARENT_ID'));
	}
	public function store($ID = NULL) {
		$input = Input::all();
		$rules['link_showing_name'] = 'required';
		$rules['link_address'] = 'required';
		$rules['shownig_order'] = 'required';
		$rules['link_type'] = 'required';
		$errorMsg = "Opps ! Some Error Occured. Please Try Again.";
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
		}
		// $input['parent_menu'] = 0;
		if ($ID != '') {
			$data = HeaderMenu::find($ID);
			if (count((array) $data) == 0) {
				$messgae = "This is not valid action,Header data not found.";
				return redirect('admin.header')->with('error', trans($messgae));
			}
		}
		$header = HeaderMenu::updateOrCreate(['hd_id' => $ID], $input);
		if ($ID != NULL) {
			$messgae = "Header Menu Updated Successfully";
		} else {
			$messgae = "Header Menu Created Successfully";
		}
		if ($header->save()) {
			HeaderMenu::where('hd_id', $header->id);
			$output['status'] = 'success';
			$output['success_msg'] = $messgae;
			$output['msg'] = $messgae;
			$output['msgHead'] = "Success ! ";
			$output['msgType'] = "success";
			$output['success'] = true;
			$output['slideToTop'] = true;
			$output['url'] = route('admin.header');
			return response()->json($output);
		} else {
			return Redirect::route('admin/header/create')->withInput()->with('error', trans('event/message.error.create'));
		}
	}
	public function createsubmenu($parent_menu = null, $ID = NULL) {
		$type = "sub";
		$menuTitle = HeaderMenu::find($parent_menu)->link_showing_name;
		if ($ID) {
			$route = route('edit.submenus', ['parent_menu' => $parent_menu, 'ID' => $ID]);
			//$route = URL::to('admin/header/edit/sub/2/6');
			$data = HeaderMenu::find($ID);
			if (count((array) $data) == 0) {
				$messgae = "This is not valid action, data not found.";
				return redirect('admin.header')->with('error', trans($messgae));
			}
			$data = HeaderMenu::Select('hd_id', 'page_set', 'link_type', 'link_address', 'link_showing_name', 'blank_target', 'shownig_order', 'type')->where('hd_id', $ID)->first();
			return View('admin.headermenu.create', compact('data', 'route', 'type', 'menuTitle'));
		}
		$route = route('store.submenu', ['parent_menu' => $parent_menu]);
		return View('admin.headermenu.create', compact('route', 'type', 'menuTitle'));
	}
	public function storeSubMenu($parent_menu = null, $ID = NULL) {
		//echo $parent_menu; die;
		$input = Input::all();
		$rules['link_showing_name'] = 'required';
		$rules['link_address'] = 'required';
		$rules['page_set'] = 'required';
		$rules['shownig_order'] = 'required';
		$errorMsg = "Opps ! Some Error Occured. Please Try Again.";
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'error_msg' => $errorMsg, 'slideToTop' => 'yes']);
		}
		$input['parent_menu'] = $parent_menu;
		if ($ID != '') {
			$data = HeaderMenu::find($ID);
			if (count((array) $data) == 0) {
				$messgae = "This is not valid action,Header data not found.";
				return redirect('admin.header')->with('error', trans($messgae));
			}
		}
		$header = HeaderMenu::updateOrCreate(['hd_id' => $ID], $input);
		if ($ID != NULL) {
			$messgae = "Header Sub Menu Updated Successfully";
		} else {
			$messgae = "Header Sub Menu Created Successfully";
		}
		if ($header->save()) {
			HeaderMenu::where('hd_id', $header->id);
			$output['status'] = 'success';
			$output['step'] = 'step1';
			$output['success'] = true;
			$output['slideToTop'] = true;
			$output['success_msg'] = $messgae;
			$output['url'] = route('admin.header');
			echo json_encode($output);die;
		} else {
			return Redirect::route('admin/header/create')->withInput()->with('error', trans('event/message.error.create'));
		}
	}
	public function getModalFinalDelete($id = null) {
		$model = 'Header';
		$confirm_route = $error = null;
		$confirm_route = route('finaldelete.header', ['hd_id' => $id]);
		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
	}
	public function permanentDelete($id) {
		HeaderMenu::where('hd_id', $id)->delete();
		$success = "Header Permanently Deleted Succesfully";
		return Redirect::route('admin.header')->with('success', $success);
	}
	public function setorderlist() {
		$header = HeaderMenu::get();
		return view('admin.headermenu.orderlist', compact('header'));
	}
	public function setOrder() {
		echo "ds";die;
		$order = Input::get('order');
		foreach ($order as $key => $value) {
			DB::table('header_links')
				->where('hd_id', $value['id']) // find your user by their email
				->update(array('shownig_order' => $value['val']));
		}
	}
}