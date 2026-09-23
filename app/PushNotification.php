<?php namespace App;

use App\Services\FcmV1;
use Redirect;
use DB;
use URL;
class PushNotification {

	/**
	 * Push one message to one or more device tokens over FCM v1.
	 *
	 * $screen is one of App\Services\PushScreens's fixed keys (or null) - it
	 * rides in the data payload as "screen" so the client can navigate
	 * straight to the right page when the notification is tapped.
	 */
	public static function send($message, $deviceToken, $screen = null)
	{
		$tokens = is_array($deviceToken) ? $deviceToken : array($deviceToken);

		foreach ($tokens as $token) {
			if (empty($token) || $token === '0' || $token === '1') {
				// "0"/"1" are the placeholder values written when a device
				// never got a real push token (Expo Go, permission denied).
				continue;
			}
			FcmV1::send($token, $message->title, $message->description, ['screen' => $screen ?? '']);
		}
	}


	public static function getAllTokenDevicesNew($memIds)
	{
		//->where('notification_status','On')
		return DB::table('users')->whereIn('id', $memIds)->pluck('DeviceToken')->toArray();
		//return DB::table('device_tokens')->whereIn('user_id', $memIds)->get();
	}


}
