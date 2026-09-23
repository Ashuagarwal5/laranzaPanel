<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use View;
use App\Message as app_modal;
use App\SendMessage;
use App\WhatsappConfiguration;
use App\WhatsappTemplate;
use App\Services\CelitixSms;
use App\Services\CelitixWhatsapp;
use App\Services\MessageEvents;
use App\Services\MessageTokens;
use App\Services\PushScreens;
use Validator;
use DB;
use Datatables;
use Redirect;
use App\Helpers\datehelper;
class MessageController extends Controller{
	function __construct()
	{
		$this->date=datehelper::dateformat();
		$this->manager_name  = 'Message';
		$this->folder_name   = 'message';
		$this->manager_url   = route('admin.message');
		$this->route_data      = route('admin.message.data');
		$this->route_create      = route('create.message');

	}
	//================== Admin   View Function ==================//
	public function index(){
		$manager_name  = $this->manager_name;
		$PARENT_ID     = 135;
		$route_data    = $this->route_data;
		$route_create    = $this->route_create;
		$folder_name    = $this->folder_name;
		return view('admin.'.$this->folder_name.'.list',compact('PARENT_ID','folder_name','manager_name','route_create','route_data'));
	}
	public function data()
	{
		$data = app_modal::select(['id', 'title', 'recipient', 'mode', 'created_at'])->orderBy('id', 'desc');
		return Datatables::of($data)
		->addIndexColumn()
		->editColumn('title', function ($row) {
			$html = e($row->title);
			if (!MessageEvents::exists($row->title)) {
				$html .= ' <span class="label label-warning" title="Nothing in the app fires this event, so it never sends.">not fired by app</span>';
			}
			return $html;
		})
		->editColumn('recipient', function ($row) {
			return e(app_modal::recipientLabel($row->recipient));
		})
		->editColumn('mode', function ($row) {
			$badges = array_map([app_modal::class, 'modeBadge'], $row->modes());
			return $badges ? implode(' ', $badges) : '<span class="text-muted">None</span>';
		})
		->addColumn('actions', function ($row) {
			return '<div class="btn-group">
				<a class="btn btn-primary" href="'.url('cpmin/message/edit/'.$row->id).'" title="Edit"><i class="fa fa-edit"></i> </a>
				<a class="btn btn-success" href="'.url('cpmin/message/show/'.$row->id).'" title="View"><i class="fa fa-eye"></i> </a>
				<a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="'.url('cpmin/message/'.$row->id.'/confirm-delete').'" title="Delete"><i class="fa fa-trash"></i> </a>
				</div>';
		})
		->rawColumns(['title', 'mode', 'actions'])
		->make(true);
	}
	public function create($id=null)
	{
		$manager_name  = $this->manager_name;
		$PARENT_ID     = 135;
		$manager_url   = $this->manager_url;

		$data = null;
		if ($id != null) {
			$data = app_modal::where('id', $id)->first();
			if (empty($data)) {
				return Redirect($this->manager_url)->with('error', 'Message not found.');
			}
		}

		// One message per event and recipient: "event|recipient" pairs already
		// configured are not offered again.
		$taken = app_modal::whereNotNull('title')
			->when($data, function ($query) use ($data) { return $query->where('id', '!=', $data->id); })
			->get(['title', 'recipient'])
			->map(function ($row) { return $row->title.'|'.($row->recipient ?: 'user'); })
			->all();
		$recipients = app_modal::recipients();
		$eventGroups = MessageEvents::groups();
		$adminEvents = MessageEvents::adminEvents();
		$tokenGroups = MessageTokens::groups();
		$pushScreens = PushScreens::options();

		$whatsapp         = $data ? $data->whatsappConfiguration() : null;
		$whatsappTemplate = $whatsapp ? $whatsapp->template() : null;
		$categories       = WhatsappTemplate::categories();
		// Only the saved category's templates matter on an edit; the rest are
		// pulled over ajax as soon as the category changes.
		$templates        = $whatsapp ? WhatsappTemplate::approved($whatsapp->category) : collect();
		$templateCount    = WhatsappTemplate::where('approval_status', 'APPROVED')->count();
		$lastSynced       = WhatsappTemplate::max('synced_at');

		return view('admin.'.$this->folder_name.'.create',compact('data','PARENT_ID','manager_name','manager_url','taken','recipients','eventGroups','adminEvents','tokenGroups','pushScreens','whatsapp','whatsappTemplate','categories','templates','templateCount','lastSynced'));
	}
	public function show($id)
	{
		$data = app_modal::where('id', $id)->first();
		if (empty($data)) {
			return Redirect($this->manager_url)->with('error', 'Message not found.');
		}
		$manager_name     = $this->manager_name;
		$PARENT_ID        = 135;
		$manager_url      = $this->manager_url;
		$tokens           = MessageTokens::all();
		$smsVariables     = CelitixSms::variables($data->sms_message);
		$whatsapp         = $data->whatsappConfiguration();
		$whatsappTemplate = $whatsapp ? $whatsapp->template() : null;
		return view('admin.'.$this->folder_name.'.show', compact('data','PARENT_ID','manager_name','manager_url','tokens','smsVariables','whatsapp','whatsappTemplate'));
	}
	public function getModalDelete($id = null)
	{
		$model = 'Message';
		$confirm_route = $error = null;
		$confirm_route = route('delete.message', ['id' => $id]);
		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
	}
	public function destroy($id)
	{
		app_modal::where('id',$id)->delete();
		WhatsappConfiguration::where('message_id', $id)->delete();
		$success ="Message Deleted Successfully.";
		$route = route('admin.message');
		return Redirect($route)->with('success', $success);
	}
	public function store(Request $request,$id=null)
	{
		$record = null;
		if ($id != null) {
			$record = app_modal::where('id', $id)->first();
			if (empty($record)) {
				return response()->json(['errorArray' => [], 'error_msg' => 'Message not found.']);
			}
		}

		// Errors are keyed by the exact input name so formClass.js can mark the
		// field, including the array inputs of the variable tables.
		$errors = array();

		$event     = trim((string) $request->event);
		$recipient = trim((string) $request->recipient);
		if (!array_key_exists($recipient, app_modal::recipients())) {
			$errors['recipient'] = 'Please select who receives this message.';
		}
		if ($event === '') {
			$errors['event'] = 'Please select the event trigger.';
		} elseif (!MessageEvents::exists($event) && !($record && $record->title === $event)) {
			$errors['event'] = 'Please select an event from the list.';
		} elseif (!isset($errors['recipient']) && app_modal::where('title', $event)->where('recipient', $recipient)->when($record, function ($query) use ($record) { return $query->where('id', '!=', $record->id); })->exists()) {
			$errors['recipient'] = 'A message for this event to '.app_modal::recipientLabel($recipient).' already exists - edit that one instead.';
		}

		$smsOn      = $request->input('sms_enabled') == '1';
		$whatsappOn = $request->input('whatsapp_enabled') == '1';
		$appOn      = $request->input('app_enabled') == '1';
		if (!$smsOn && !$whatsappOn && !$appOn) {
			$errors['channels'] = 'Enable at least one of SMS, WhatsApp or App Notification.';
		}

		// --- SMS ---------------------------------------------------------
		$smsTemplateId = trim((string) $request->sms_template_id);
		$smsSenderId   = strtoupper(trim((string) $request->sms_sender_id));
		$smsMessage    = trim((string) $request->sms_message);
		$smsPosted     = is_array($request->sms_variables) ? $request->sms_variables : array();
		// Keep only the positions the text declares, so a value left over from
		// an edited-out placeholder is not stored.
		$smsMapping = array();
		foreach (CelitixSms::variables($smsMessage) as $position => $placeholder) {
			$smsMapping[$position] = isset($smsPosted[$position]) ? trim((string) $smsPosted[$position]) : '';
		}
		if ($smsOn) {
			if ($smsTemplateId === '') {
				$errors['sms_template_id'] = 'Please enter the SMS Template ID.';
			} elseif (!ctype_digit($smsTemplateId)) {
				$errors['sms_template_id'] = 'SMS Template ID should contain digits only.';
			}
			if ($smsSenderId === '') {
				$errors['sms_sender_id'] = 'Please enter the SMS Sender ID.';
			} elseif (!ctype_alnum($smsSenderId)) {
				$errors['sms_sender_id'] = 'SMS Sender ID should contain letters and digits only.';
			}
			if ($smsMessage === '') {
				$errors['sms_message'] = 'Please enter the SMS message.';
			}
			foreach ($smsMapping as $position => $value) {
				if ($value === '') {
					$errors['sms_variables['.$position.']'] = 'Please choose a value for SMS variable #'.$position.'.';
				}
			}
		}

		// --- WhatsApp ----------------------------------------------------
		$template = WhatsappTemplate::where('id', $request->whatsapp_template)->where('approval_status', 'APPROVED')->first();
		$waPosted = is_array($request->whatsapp_variables) ? $request->whatsapp_variables : array();
		$waMapping = array();
		if ($template) {
			foreach ($template->variablePositions() as $position) {
				$waMapping[$position] = isset($waPosted[$position]) ? trim((string) $waPosted[$position]) : '';
			}
		}
		if ($whatsappOn) {
			if (trim((string) $request->whatsapp_category) === '') {
				$errors['whatsapp_category'] = 'Please select the WhatsApp template category.';
			}
			if (empty($template)) {
				$errors['whatsapp_template'] = 'Please select an approved WhatsApp template.';
			}
			foreach ($waMapping as $position => $value) {
				if ($value === '') {
					$errors['whatsapp_variables['.$position.']'] = 'Please choose a value for WhatsApp variable '.$position.'.';
				}
			}
		}

		// --- App Notification -------------------------------------------
		$appMessage = trim((string) $request->app_message);
		if ($appOn && $appMessage === '') {
			$errors['app_message'] = 'Please enter the app notification message.';
		}
		$appTargetScreen = trim((string) $request->app_target_screen);
		if (!PushScreens::exists($appTargetScreen)) {
			$errors['app_target_screen'] = 'Please choose a valid screen.';
		}

		if (!empty($errors)) {
			return response()->json(['errorArray' => $errors, 'error_msg' => 'Please fix the highlighted fields.', 'slideToTop' => 'yes']);
		}

		DB::transaction(function () use ($record, $event, $recipient, $smsOn, $whatsappOn, $appOn, $smsTemplateId, $smsSenderId, $smsMessage, $smsMapping, $template, $waMapping, $appMessage, $appTargetScreen, $request) {
			$data = $record ?: new app_modal;
			$data->title                = $event;
			$data->recipient            = $recipient;
			$data->sms_template_id      = $smsTemplateId ?: null;
			$data->sms_sender_id        = $smsSenderId ?: null;
			$data->sms_message          = $smsMessage ?: null;
			$data->sms_variable_mapping = json_encode($smsMapping, JSON_FORCE_OBJECT);
			$data->app_message          = $appMessage ?: null;
			$data->app_target_screen    = $appTargetScreen ?: null;
			// The legacy columns stay filled for anything still reading them.
			$data->message              = $appMessage ?: $smsMessage;
			$data->template_id          = $smsTemplateId ?: null;
			$data->mode                 = implode(',', array_keys(array_filter(['Sms' => $smsOn, 'WhatsApp' => $whatsappOn, 'AppNotification' => $appOn])));
			$data->save();

			// Content is kept while a channel is switched off, so turning it
			// back on does not mean filling the tab in again.
			$config = $data->whatsappConfiguration();
			if ($template) {
				$config = $config ?: new WhatsappConfiguration;
				$config->name             = $event.' ('.app_modal::recipientLabel($recipient).')';
				$config->message_id       = $data->id;
				$config->category         = $request->whatsapp_category ?: $template->category;
				$config->template_name    = $template->template_name;
				$config->language         = $template->language;
				$config->variable_mapping = json_encode($waMapping);
				$config->status           = $whatsappOn ? 'Active' : 'Inactive';
				$config->save();
			} elseif ($config) {
				$config->status = 'Inactive';
				$config->save();
			}
			// Only one mapping may be live per event.
			if ($config) {
				WhatsappConfiguration::where('message_id', $data->id)->where('id', '!=', $config->id)->update(['status' => 'Inactive']);
			}
		});

		$messgae = $this->manager_name.($id != null ? " Updated Successfully" : " Created Successfully");

		$output['status'] = 'success';
		$output['success_msg'] = $messgae;
		$output['msg'] = $messgae;
		$output['msgHead'] = "Success ! ";
		$output['msgType'] = "success";
		$output['success'] = true;
		$output['slideToTop'] = true;
		$output['url'] = route('admin.message');
		return response()->json($output);
	}

	/**
	 * Fire one real SMS or WhatsApp so the admin can check the setup end to end.
	 * There is no customer behind a test, so tokens are sent as their own names.
	 */
	public function testSend(Request $request, $id)
	{
		$data = app_modal::where('id', $id)->first();
		if (empty($data)) {
			return response()->json(['status' => 'error', 'msg' => 'Message not found.']);
		}
		$validator = Validator::make($request->all(), ['mobileno' => 'required', 'channel' => 'required|in:Sms,WhatsApp']);
		if ($validator->fails()) {
			return response()->json(['status' => 'error', 'msg' => 'Please enter a mobile number.']);
		}

		if ($request->channel == 'Sms') {
			$text   = CelitixSms::render($data->sms_message, $data->smsMapping());
			$result = CelitixSms::send($request->mobileno, $text, $data->sms_sender_id, $data->sms_template_id, $data->id);
		} else {
			$config   = $data->whatsappConfiguration();
			$template = $config ? $config->template() : null;
			if (empty($template)) {
				return response()->json(['status' => 'error', 'msg' => 'No WhatsApp template is linked, or it is no longer available. Sync templates again.']);
			}
			$result = CelitixWhatsapp::sendTemplate($request->mobileno, $template, $config->mapping(), $data->id);
		}

		return response()->json([
			'status' => $result['status'],
			'msg'    => $result['status'] === 'success' ? 'Test message sent successfully.' : $result['msg'],
		]);
	}

	/**
	 * Templates of one category, for the WhatsApp tab's dependent dropdown.
	 */
	public function whatsappTemplates(Request $request)
	{
		$output = array();
		foreach (WhatsappTemplate::approved($request->category) as $template) {
			$output[] = [
				'id'    => $template->id,
				'label' => $template->template_name . ' (' . $template->language . ')',
			];
		}
		return response()->json(['status' => 'success', 'templates' => $output]);
	}

	/**
	 * A template's raw text and placeholders; the browser fills in the values.
	 */
	public function whatsappPreview(Request $request)
	{
		$template = WhatsappTemplate::where('id', $request->template_id)->first();
		if (empty($template)) {
			return response()->json(['status' => 'error', 'msg' => 'Template not found.']);
		}
		return response()->json([
			'status'    => 'success',
			'positions' => $template->variablePositions(),
			'raw'       => $template->preview(),
			'buttons'   => json_decode((string) $template->buttons, true),
			'footer'    => $template->footer_text,
		]);
	}

	/**
	 * Pull the Meta-approved template list from Celitix into whatsapp_templates.
	 */
	public function whatsappSync()
	{
		$result = CelitixWhatsapp::syncTemplates();
		return Redirect::back()->with($result['status'] === 'success' ? 'success' : 'error', $result['msg']);
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
}
