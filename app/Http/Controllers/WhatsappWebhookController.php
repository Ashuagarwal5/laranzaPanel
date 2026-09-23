<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WhatsappMessageLog;
use App\Services\WhatsappBotService;

/**
 * Receives delivery receipts and inbound messages from Celitix / Meta.
 * Delivery receipts stamp the message log row that owns the wamid; inbound
 * text messages are handed to WhatsappBotService (the dealer pickup flow).
 */
class WhatsappWebhookController extends Controller {

	/**
	 * Meta's subscription handshake: echo hub.challenge back verbatim.
	 */
	public function verify(Request $request)
	{
		$verifyToken = env('WP_WEBHOOK_VERIFY_TOKEN');
		if (!empty($verifyToken) && $request->input('hub_verify_token') !== $verifyToken) {
			return response('Forbidden', 403);
		}
		return response((string) $request->input('hub_challenge'), 200)
			->header('Content-Type', 'text/plain');
	}

	public function handle(Request $request)
	{
		$payload = $request->all();

		// Temporary: the inbound-message shape below assumes Celitix mirrors
		// Meta's Cloud API payload (same as our outbound send calls do). This
		// writes every raw hit to its own file (not laravel.log, so it's easy
		// to `tail`) so the assumption can be checked/corrected against a real
		// "start" message without needing Celitix's docs up front.
		$this->logWebhook($request->getContent());

		$entries = isset($payload['entry']) && is_array($payload['entry']) ? $payload['entry'] : [];

		foreach ($entries as $entry) {
			$changes = isset($entry['changes']) && is_array($entry['changes']) ? $entry['changes'] : [];
			foreach ($changes as $change) {
				$value = isset($change['value']) && is_array($change['value']) ? $change['value'] : [];

				$statuses = isset($value['statuses']) && is_array($value['statuses']) ? $value['statuses'] : [];
				foreach ($statuses as $status) {
					$this->applyStatus($status);
				}

				$messages = isset($value['messages']) && is_array($value['messages']) ? $value['messages'] : [];
				foreach ($messages as $message) {
					$this->applyMessage($message);
				}
			}
		}

		// Meta retries anything that is not a 200, so acknowledge unconditionally.
		return response()->json(['status' => 'success'], 200);
	}

	/**
	 * Plain file append, independent of the app's log config, so a webhook
	 * hit is visible with a simple `tail`/`Get-Content -Wait` regardless of
	 * how logging.php (or its absence, on this app) is set up.
	 */
	protected function logWebhook($rawBody)
	{
		$line = '['.date('Y-m-d H:i:s').'] '.$rawBody.PHP_EOL;
		@file_put_contents(storage_path('logs/whatsapp-webhook.log'), $line, FILE_APPEND | LOCK_EX);
	}

	protected function applyMessage(array $message)
	{
		if (empty($message['from']) || $message['type'] !== 'text' || empty($message['text']['body'])) {
			return;
		}

		WhatsappBotService::handleIncoming($message['from'], $message['text']['body']);
	}

	protected function applyStatus(array $status)
	{
		if (empty($status['id'])) {
			return;
		}
		$log = WhatsappMessageLog::where('wamid', $status['id'])->first();
		if (empty($log)) {
			return;
		}

		// sent -> delivered -> read only ever moves forward; a late "sent"
		// callback must not undo a delivery we already recorded.
		$order   = ['pending' => 0, 'sent' => 1, 'delivered' => 2, 'read' => 3];
		$current = isset($order[$log->status]) ? $order[$log->status] : 0;
		$next    = isset($order[$status['status']]) ? $order[$status['status']] : null;

		if ($status['status'] === 'failed') {
			$log->status = 'failed';
			$log->error  = isset($status['errors'][0]['title']) ? $status['errors'][0]['title'] : 'Delivery failed.';
		} elseif ($next !== null && $next > $current) {
			$log->status = $status['status'];
		} else {
			return;
		}

		$log->status_at = !empty($status['timestamp']) ? date('Y-m-d H:i:s', (int) $status['timestamp']) : date('Y-m-d H:i:s');
		$log->save();
	}
}
