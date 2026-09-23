<?php
namespace App\Http\Controllers\Admin;
use App\FAQ;
use App\Helpers\datehelper;
use App\Products;
use Datatables;
use DB;
use Illuminate\Http\Request;
use Redirect;
use Response;
use View;

class FAQController extends CodespurController {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	function __construct() {
		$this->date = datehelper::dateformat();
	}
	public function index() {
		$PARENT_ID = 92;
		$totalRecord = FAQ::count();
		return view('admin.FAQ.list', compact('PARENT_ID', 'totalRecord'));
	}
	//=============== All List Data Function ==========================//
	public function data() {

		$data = FAQ::select('faq.*', DB::raw("(DATE_FORMAT(`tbl_faq`.created_at,'%d %M  %Y')) as add_date"))
			->get();
		return Datatables::of($data)
			->addColumn('actions', '<a title="View Info" class="btn btn-success btn-xs purple" href="{{URL::to("admin/faq/show/$id")}}"data-toggle="modal" data-target="#modal-email">
					<i class="fa fa-eye"></i>
				</a>
				<a class="delval btn btn-xs btn-primary" title="Edit Education" href="{{URL::to("admin/faq/edit/$id")}}"><i class="fa fa-edit"></i>
				</a>
				<a data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/faq/$id/confirm-delete")}}" class="delval btn btn-xs btn-danger"  title="Delete Education">
					<i class="fa fa-trash"></i>
				</a>
              ')
			->rawColumns(['actions'])
			->make(true);

	}
	public function create($id = null) {
		$products = Products::where('deleted_at', null)->orderby('id', 'desc')->get();
		$PARENT_ID = 92;
		$data = FAQ::find($id);
		return view('admin.FAQ.edit', compact('PARENT_ID', 'data', 'products'));
	}
	public function store(Request $request, $id = null) {
		//echo "fmdk"; die;
		$this->validate($request,
			[
				'title' => 'required',
				'description' => 'required',
			]
		);

		$input = $request->all();
		if ($id != null) {
			$message = " FAQ Updated Successfully";
		} else {
			$message = "FAQ Created Successfully";
		}

		FAQ::updateOrCreate(['id' => $id], $input);

		$notification = array(
			'message' => $message,
			'alert-type' => 'success',
		);

		//return Redirect::route('admin.faq');
		return redirect('admin/faq')->with($notification);
		//return Redirect('admin/faq')->with($notification);
	}
	public function view($id) {
		$PARENT_ID = 21;
		$detail = FAQ::select('faq.*', DB::raw("(DATE_FORMAT(`tbl_faq`.created_at,'%d %M  %Y')) as add_date"), DB::raw("(DATE_FORMAT(`tbl_faq`.created_at,'%d %M  %Y')) as update_date"))
			->find($id);
		if (count($detail) == 0) {
			$notification = array(
				'message' => 'Sorry FAQ Not Found.',
				'alert-type' => 'warning',
			);
			return redirect('admin/faq')->with($notification);
		}
		return view('admin.FAQ.view', compact('PARENT_ID', 'detail'));
	}

	public function getModalDelete($id = null) {

		$model = 'FAQ';
		$confirm_route = $error = null;
		$faq = FAQ::where('id', $id)->first();
		if (empty($faq)) {
			return Redirect::route('info');
		} else {
			$confirm_route = route('delete/faq', ['id' => $id]);
			return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
		}
	}

	public function destroy($edu_id) {
		$edu = FAQ::find($edu_id);
		$res = $edu->delete();
		return Redirect::route('admin.faq');
	}

}
