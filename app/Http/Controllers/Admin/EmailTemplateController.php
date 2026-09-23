<?php
namespace App\Http\Controllers\Admin;
use App\EmailTemplate;
use App\Helpers\datehelper;
use App\Http\Requests\EmailtemplateRequest;
use Datatables;
use DB;
use Redirect;
use View;

class EmailTemplateController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	function __construct() {
		$this->date = datehelper::dateformat();
	}
	public function index() {
		$PARENT_ID = 98;
		return view('admin.emailtemplates.list', compact('PARENT_ID'));
	}
	public function data() {

		$Emailtemplate = EmailTemplate::select(['em_tm_id', 'title', 'subject', 'message', DB::raw("(DATE_FORMAT(created_at,$this->date)) as add_date")])->orderBy('em_tm_id', 'desc');
		return Datatables::of($Emailtemplate)
			->addColumn('actions', '<div class="btn-group"><a title="View Info" class="btn btn-success" href="{{ route(\'emailtemplate/view\',$em_tm_id) }}"data-toggle="modal" data-target="#modal-email">
          <i class="fa fa-eye"></i>
               View
              </a>
               <a class="btn btn-primary" title="Edit Email Template" href="{{ route(\'update/emailtemplate\', $em_tm_id) }}">
          <i class="fa fa-edit"></i>
               Edit
              </a></div>
              ')
			->rawColumns(['actions'])
			->make(true);
	}
	public function viewRecord($em_tm_id) {
		$PARENT_ID = 98;

		$detail = EmailTemplate::select(['em_tm_id', 'title', 'subject', 'message', 'instructions', DB::raw("DATE_FORMAT(created_at,$this->date) as add_date")])
			->where('em_tm_id', $em_tm_id)->first();

		return View('admin.emailtemplates.view', compact('detail', 'PARENT_ID'));
	}
	public function edit($em_tm_id = NULL) {
		$PARENT_ID = 98;
		if ($em_tm_id) {
			$detail = EmailTemplate::Select('em_tm_id', 'title', 'subject', 'message', 'instructions')->where('em_tm_id', $em_tm_id)->first();
			return View('admin.emailtemplates.edit', compact('detail', 'em_tm_id', 'PARENT_ID'));
		} else {
			return View('admin.emailtemplates.edit', compact('PARENT_ID'));
		}
	}
	public function editPost(EmailtemplateRequest $request, $em_tm_id = NULL) {
		$input = $request->all();
		if ($em_tm_id != NULL) {
			$messgae = "Email Template Updated Successfully.";
		} else {
			$messgae = "Email Template Create Successfully.";
		}
		$Emailtemplate = EmailTemplate::updateOrCreate(['em_tm_id' => $em_tm_id], $input);
		return redirect('admin/emailtemplate')->with('success', trans($messgae));
	}
}
