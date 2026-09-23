<?php namespace App\Services;

use App\WebsiteSetting;
use App\WhatsappTemplate;
use App\WhatsappMessageLog;

/**
 * Thin wrapper over the Celitix WABA API.
 *
 * Both endpoints authenticate with the same two headers - the portal API key
 * and the registered WABA number - which live in Message Setting.
 */
class CelitixWhatsapp {

	/**
	 * Credentials come from website_settings so they stay editable in admin.
	 */
	protected static function credentials()
	{
		$settings = WebsiteSetting::getGeneralSetting();
		return [
			'key'        => $settings ? trim((string) $settings->whatsaap_api_key) : '',
			'wabaNumber' => $settings ? preg_replace('/[^0-9]/', '', (string) $settings->waba_number) : '',
		];
	}

	protected static function url($path)
	{
		return rtrim(config('whatsapp.base_url'), '/') . '/' . ltrim($path, '/');
	}

	/**
	 * POST/GET against Celitix. Returns ['status', 'code', 'body', 'raw'].
	 */
	protected static function call($url, $payload = null)
	{
		$credentials = self::credentials();
		if (empty($credentials['key']) || empty($credentials['wabaNumber'])) {
			return ['status' => 'error', 'code' => 0, 'body' => null, 'raw' => '', 'msg' => 'WhatsApp API Key and Waba Number are not set in Message Setting.'];
		}

		$headers = [
			'Content-type: application/json',
			'key: ' . $credentials['key'],
			'wabaNumber: ' . $credentials['wabaNumber'],
		];

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		if ($payload !== null) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
		}
		$raw  = curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$err  = curl_error($ch);
		curl_close($ch);

		if ($raw === false) {
			return ['status' => 'error', 'code' => $code, 'body' => null, 'raw' => '', 'msg' => $err ?: 'Could not reach the WhatsApp API.'];
		}

		$body = json_decode($raw, true);
		$ok   = ($code == 200 || $code == 202);

		return [
			'status' => $ok ? 'success' : 'error',
			'code'   => $code,
			'body'   => $body,
			'raw'    => $raw,
			'msg'    => $ok ? '' : self::errorMessage($code, $body),
		];
	}

	protected static function errorMessage($code, $body)
	{
		if (is_array($body)) {
			if (isset($body['error']['message'])) {
				return $body['error']['message'];
			}
			if (isset($body['message'])) {
				return is_string($body['message']) ? $body['message'] : json_encode($body['message']);
			}
		}
		$known = [
			401 => 'Unauthorized - check the API key.',
			403 => 'Forbidden - the WABA number is not permitted for this key.',
			405 => 'Method not allowed.',
			429 => 'Too many requests - please retry in a moment.',
			500 => 'Celitix internal server error.',
		];
		return isset($known[$code]) ? $known[$code] : 'WhatsApp API returned HTTP ' . $code . '.';
	}

	/**
	 * Pull the Meta-approved template list and mirror it into whatsapp_templates.
	 */
	public static function syncTemplates()
	{
		$response = self::call(self::url('/wrapper/waba/getTemplateList'));
		if ($response['status'] !== 'success') {
			return ['status' => 'error', 'msg' => $response['msg'], 'synced' => 0];
		}

		$templates = isset($response['body']['data']) && is_array($response['body']['data']) ? $response['body']['data'] : [];
		$synced = 0;
		$seen   = [];

		foreach ($templates as $template) {
			if (empty($template['name'])) {
				continue;
			}
			$parts    = self::splitComponents(isset($template['components']) ? $template['components'] : []);
			$language = !empty($template['language']) ? $template['language'] : 'en';

			$record = WhatsappTemplate::withTrashed()
				->where('template_name', $template['name'])
				->where('language', $language)
				->first();
			if (empty($record)) {
				$record = new WhatsappTemplate();
			}

			$record->deleted_at       = null;
			$record->template_uid     = isset($template['id']) ? $template['id'] : null;
			$record->template_name    = $template['name'];
			$record->language         = $language;
			$record->category         = isset($template['category']) ? $template['category'] : null;
			$record->parameter_format = isset($template['parameter_format']) ? $template['parameter_format'] : 'POSITIONAL';
			$record->header_type      = $parts['header_type'];
			$record->header_text      = $parts['header_text'];
			$record->body_text        = $parts['body_text'];
			$record->footer_text      = $parts['footer_text'];
			$record->buttons          = json_encode($parts['buttons']);
			$record->components       = json_encode(isset($template['components']) ? $template['components'] : []);
			$record->approval_status  = isset($template['status']) ? $template['status'] : 'APPROVED';
			$record->synced_at        = date('Y-m-d H:i:s');
			$record->save();

			$record->variable_count = count($record->variablePositions());
			$record->save();

			$seen[] = $record->id;
			$synced++;
		}

		// Templates deleted in the Celitix panel should stop being offered here.
		if (!empty($seen)) {
			WhatsappTemplate::whereNotIn('id', $seen)->delete();
		}

		return ['status' => 'success', 'msg' => $synced . ' template(s) synced.', 'synced' => $synced];
	}

	/**
	 * Flatten Celitix's component array into the columns we display.
	 */
	protected static function splitComponents($components)
	{
		$parts = ['header_type' => null, 'header_text' => null, 'body_text' => null, 'footer_text' => null, 'buttons' => []];
		if (!is_array($components)) {
			return $parts;
		}
		foreach ($components as $component) {
			$type = isset($component['type']) ? strtoupper($component['type']) : '';
			if ($type == 'HEADER') {
				$parts['header_type'] = isset($component['format']) ? $component['format'] : 'TEXT';
				$parts['header_text'] = isset($component['text']) ? $component['text'] : null;
			} elseif ($type == 'BODY') {
				$parts['body_text'] = isset($component['text']) ? $component['text'] : null;
			} elseif ($type == 'FOOTER') {
				$parts['footer_text'] = isset($component['text']) ? $component['text'] : null;
			} elseif ($type == 'BUTTONS') {
				$parts['buttons'] = isset($component['buttons']) ? $component['buttons'] : [];
			}
		}
		return $parts;
	}

	/**
	 * Send one template message.
	 *
	 * $values is position => text, e.g. [1 => 'Ashutosh', 2 => '250'].
	 */
	public static function sendTemplate($mobileno, WhatsappTemplate $template, array $values = [], $messageId = null)
	{
		$to = self::normaliseNumber($mobileno);
		if (empty($to)) {
			return ['status' => 'error', 'msg' => 'Recipient mobile number is empty or invalid.'];
		}

		$payload = [
			'messaging_product' => 'whatsapp',
			'recipient_type'    => 'individual',
			'to'                => $to,
			'type'              => 'template',
			'template'          => [
				'name'     => $template->template_name,
				'language' => ['code' => $template->language],
			],
		];

		// A template with no placeholders must be sent without a components key.
		$components = self::buildComponents($template, $values);
		if (!empty($components)) {
			$payload['template']['components'] = $components;
		}

		$log = new WhatsappMessageLog();
		$log->mobileno      = $to;
		$log->template_name = $template->template_name;
		$log->message_id    = $messageId;
		$log->payload       = json_encode($payload);
		$log->status        = 'pending';
		$log->save();

		$response = self::call(self::url(config('whatsapp.endpoint')), $payload);

		$log->response  = $response['raw'];
		$log->status    = $response['status'] === 'success' ? 'sent' : 'failed';
		$log->error     = $response['status'] === 'success' ? null : $response['msg'];
		$log->status_at = date('Y-m-d H:i:s');
		$log->wamid     = self::extractWamid($response['body']);
		$log->save();

		return [
			'status' => $response['status'],
			'msg'    => $response['msg'],
			'wamid'  => $log->wamid,
			'log_id' => $log->id,
		];
	}

	/**
	 * Send one free-text (session) message - only deliverable inside the 24h
	 * customer-service window Meta opens after the user messages us, e.g. a
	 * chatbot reply to an inbound WhatsApp message. Unlike sendTemplate(), no
	 * pre-approved template is needed.
	 */
	public static function sendText($mobileno, $body)
	{
		$to = self::normaliseNumber($mobileno);
		if (empty($to)) {
			return ['status' => 'error', 'msg' => 'Recipient mobile number is empty or invalid.'];
		}

		$payload = [
			'messaging_product' => 'whatsapp',
			'recipient_type'    => 'individual',
			'to'                => $to,
			'type'              => 'text',
			'text'              => ['body' => (string) $body],
		];

		$log = new WhatsappMessageLog();
		$log->mobileno = $to;
		$log->payload  = json_encode($payload);
		$log->status   = 'pending';
		$log->save();

		$response = self::call(self::url(config('whatsapp.endpoint')), $payload);

		$log->response  = $response['raw'];
		$log->status    = $response['status'] === 'success' ? 'sent' : 'failed';
		$log->error     = $response['status'] === 'success' ? null : $response['msg'];
		$log->status_at = date('Y-m-d H:i:s');
		$log->wamid     = self::extractWamid($response['body']);
		$log->save();

		return [
			'status' => $response['status'],
			'msg'    => $response['msg'],
			'wamid'  => $log->wamid,
			'log_id' => $log->id,
		];
	}

	/**
	 * Body parameters in {{1}}, {{2}} ... order, plus the button parameter that
	 * authentication (OTP) templates require alongside the body one.
	 */
	protected static function buildComponents(WhatsappTemplate $template, array $values)
	{
		$positions = $template->variablePositions();
		if (empty($positions)) {
			return [];
		}

		// A NAMED template ({{user_name}}) needs parameter_name on every
		// parameter; a POSITIONAL one ({{1}}) must not carry it.
		$named = strtoupper((string) $template->parameter_format) === 'NAMED'
			|| count(array_filter($positions, 'is_string')) > 0;

		$parameters = [];
		foreach ($positions as $position) {
			$parameter = [
				'type' => 'text',
				'text' => self::cleanParameter(isset($values[$position]) ? $values[$position] : ''),
			];
			if ($named) {
				$parameter['parameter_name'] = (string) $position;
			}
			$parameters[] = $parameter;
		}

		$components = [[
			'type'       => 'body',
			'parameters' => $parameters,
		]];

		// Meta copy-code/URL buttons on an auth template take the same value as
		// the body's first parameter.
		if (strtoupper((string) $template->category) === 'AUTHENTICATION') {
			$buttons = json_decode((string) $template->buttons, true);
			if (!empty($buttons)) {
				$subType = isset($buttons[0]['type']) && strtoupper($buttons[0]['type']) === 'URL' ? 'url' : 'copy_code';
				$components[] = [
					'type'       => 'button',
					'sub_type'   => $subType,
					'index'      => 0,
					'parameters' => [$parameters[0]],
				];
			}
		}

		return $components;
	}

	/**
	 * Meta rejects a parameter containing a newline, a tab, or more than four
	 * consecutive spaces, so multi-line values (an address, a long remark) are
	 * flattened onto one line before they are sent.
	 */
	protected static function cleanParameter($value)
	{
		$text = trim(preg_replace('/\s+/u', ' ', (string) $value));
		return $text;
	}

	/**
	 * Celitix expects 91XXXXXXXXXX - digits only, no plus, country code included.
	 */
	public static function normaliseNumber($mobileno)
	{
		$digits = preg_replace('/[^0-9]/', '', (string) $mobileno);
		if (strlen($digits) == 10) {
			$digits = '91' . $digits;
		}
		if (strlen($digits) < 11 || strlen($digits) > 15) {
			return null;
		}
		return $digits;
	}

	protected static function extractWamid($body)
	{
		if (isset($body['messages'][0]['id'])) {
			return $body['messages'][0]['id'];
		}
		if (isset($body['data']['messages'][0]['id'])) {
			return $body['data']['messages'][0]['id'];
		}
		return null;
	}
}
