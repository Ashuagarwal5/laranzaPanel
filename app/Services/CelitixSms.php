<?php namespace App\Services;

use App\WebsiteSetting;
use App\SmsMessageLog;

/**
 * Celitix SMS API.
 *
 * One GET to {base_url}/v1/sms/send/ with everything in the query string. The
 * API key is the same one WhatsApp uses; the DLT Entity ID lives beside it in
 * Message Setting, while Sender ID and Template ID belong to each message.
 */
class CelitixSms {

	/**
	 * A DLT variable as it appears in the approved text: {#var#}, and the
	 * {{#var1}} / {{#var1#}} spellings some panels export.
	 */
	const VARIABLE_PATTERN = '/\{\{?#\s*[A-Za-z0-9_]*\s*#?\}\}?/';

	/**
	 * Placeholders in the order they appear, keyed 1, 2, 3...
	 *
	 * DLT names every variable "var", so they are told apart by position.
	 */
	public static function variables($message)
	{
		preg_match_all(self::VARIABLE_PATTERN, (string) $message, $matches);
		$variables = array();
		foreach ($matches[0] as $index => $placeholder) {
			$variables[$index + 1] = $placeholder;
		}
		return $variables;
	}

	/**
	 * Replace the nth placeholder with $values[n].
	 */
	public static function render($message, array $values)
	{
		$position = 0;
		return preg_replace_callback(self::VARIABLE_PATTERN, function ($match) use ($values, &$position) {
			$position++;
			return isset($values[$position]) ? $values[$position] : '';
		}, (string) $message);
	}

	public static function send($mobileno, $message, $senderId, $templateId, $messageId = null)
	{
		$settings = WebsiteSetting::getGeneralSetting();
		// SMS has its own key; the WhatsApp key is only a fallback while it is empty.
		$apiKey   = $settings ? trim((string) ($settings->sms_api_key ?: $settings->whatsaap_api_key)) : '';
		$entityId = $settings ? trim((string) $settings->sms_entity_id) : '';
		$to       = CelitixWhatsapp::normaliseNumber($mobileno);

		SmsMessageLog::ensureSchema();

		$log = new SmsMessageLog();
		$log->mobileno    = $to ?: $mobileno;
		$log->message_id  = $messageId;
		$log->sender_id   = $senderId;
		$log->template_id = $templateId;
		$log->event       = $messageId ? \App\Message::withTrashed()->where('id', $messageId)->value('title') : null;
		$log->status      = 'pending';

		$error = null;
		if (empty($apiKey) || empty($entityId)) {
			$error = 'API Key and SMS Entity ID are not set in Message Setting.';
		} elseif (empty($to)) {
			$error = 'Recipient mobile number is empty or invalid.';
		} elseif (empty($senderId) || empty($templateId) || trim((string) $message) === '') {
			$error = 'Sender ID, Template ID and message text are all needed to send an SMS.';
		}
		if ($error) {
			$log->status = 'failed';
			$log->error  = $error;
			$log->save();
			SmsMessageLog::prune($log->id);
			return ['status' => 'error', 'msg' => $error, 'log_id' => $log->id];
		}

		$query = http_build_query([
			'message'  => $message,
			'mobile'   => $to,
			'senderid' => $senderId,
			'entityid' => $entityId,
			'tempid'   => $templateId,
			'apikey'   => $apiKey,
		], '', '&', PHP_QUERY_RFC3986);
		$url = rtrim(config('whatsapp.base_url'), '/') . '/' . ltrim(config('whatsapp.sms_endpoint'), '/') . '?' . $query;

		$log->request = str_replace(rawurlencode($apiKey), '***', $url);
		$log->save();

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		$raw  = curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$err  = curl_error($ch);
		curl_close($ch);

		$body = $raw === false ? null : json_decode($raw, true);
		$item = isset($body['data'][0]) && is_array($body['data'][0]) ? $body['data'][0] : null;
		// Celitix answers 200 even for a rejected number; code "000" is the success.
		$ok   = $item && isset($item['code']) && (string) $item['code'] === '000';

		$log->response            = $raw === false ? null : $raw;
		$log->status              = $ok ? strtolower(isset($item['status']) ? $item['status'] : 'queued') : 'failed';
		$log->provider_message_id = isset($item['messageId']) ? $item['messageId'] : null;
		$log->client_ref_id       = isset($item['clientRefId']) ? $item['clientRefId'] : null;
		$log->error               = $ok ? null : self::errorMessage($raw, $code, $err, $body, $item);
		$log->save();
		SmsMessageLog::prune($log->id);

		return [
			'status'     => $ok ? 'success' : 'error',
			'msg'        => $ok ? '' : $log->error,
			'message_id' => $log->provider_message_id,
			'log_id'     => $log->id,
		];
	}

	protected static function errorMessage($raw, $code, $curlError, $body, $item)
	{
		if ($raw === false) {
			return $curlError ?: 'Could not reach the SMS API.';
		}
		if ($item && !empty($item['reason'])) {
			return $item['reason'] . (isset($item['code']) ? ' (code ' . $item['code'] . ')' : '');
		}
		if (is_array($body) && isset($body['message'])) {
			return is_string($body['message']) ? $body['message'] : json_encode($body['message']);
		}
		return 'SMS API returned HTTP ' . $code . '.';
	}
}
