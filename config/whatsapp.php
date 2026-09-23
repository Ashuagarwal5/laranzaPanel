<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Celitix WABA wrapper API
	|--------------------------------------------------------------------------
	|
	| Celitix exposes a wrapper over the WhatsApp Business API. Templates are
	| authored and submitted for Meta approval in their panel; we only pick an
	| approved template here and send it with our own variable values.
	|
	| The same base URL and API key also serve Celitix SMS.
	|
	*/

	'base_url' => env('WP_BASE_URL', 'https://api.celitix.com'),
	'endpoint' => trim(env('WP_ENDPOINT', '/wrapper/waba/message')),
	'sms_endpoint' => trim(env('SMS_ENDPOINT', '/v1/sms/send/')),

];
