<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\WhatsappMessageLog;
use App\SmsMessageLog;

/**
 * Admin "Logs" page: the history of every WhatsApp message and every SMS the
 * app has tried to send, with the status the provider reported.
 *
 * - WhatsApp History: rows of whatsapp_message_logs, written by
 *   CelitixWhatsapp::sendTemplate() / sendText() each time a message is sent,
 *   and updated to delivered / read / failed by the Celitix webhook.
 * - SMS History: rows of sms_message_logs, written by CelitixSms::send().
 *
 * Both tables keep only the newest rows (see the prune() methods on the models).
 */
class WhatsappLogController extends Controller
{
	public function index(Request $request)
	{
		$PARENT_ID = 135;
		$tab       = $request->input('tab', 'whatsapp');
		if (!in_array($tab, ['whatsapp', 'sms'])) {
			$tab = 'whatsapp';
		}

		$whatsappRows = collect();
		$smsRows      = collect();
		if ($tab === 'sms') {
			SmsMessageLog::ensureSchema();
			$smsRows = SmsMessageLog::orderBy('id', 'desc')->limit(200)->get();
		} else {
			$whatsappRows = WhatsappMessageLog::orderBy('id', 'desc')->limit(200)->get();
		}

		return view('admin.message.whatsapp-logs', compact('PARENT_ID', 'tab', 'whatsappRows', 'smsRows'));
	}

	public function clear(Request $request)
	{
		$tab = $request->input('tab');

		if ($tab === 'sms') {
			SmsMessageLog::query()->delete();
			$done = 'SMS history cleared.';
		} else {
			$tab = 'whatsapp';
			WhatsappMessageLog::query()->delete();
			$done = 'WhatsApp history cleared.';
		}

		return redirect()->route('admin.whatsapp.logs', ['tab' => $tab])->with('log_message', $done);
	}
}
