<?php namespace App\Services;

/**
 * The events the application fires SendMessage::getSendMessage() with.
 *
 * The key is the exact string the code passes, and is stored as
 * messages_info.title. A message saved against any other title never sends,
 * because nothing triggers it.
 *
 * ---------------------------------------------------------------------------
 * TO ADD A NEW EVENT
 * ---------------------------------------------------------------------------
 *   1. Call SendMessage::getSendMessage('My Event', $userId, ...) where it happens.
 *   2. Add 'My Event' => 'When it fires' to the right group below.
 */
class MessageEvents {

	/**
	 * Grouped event => description, for the dropdown.
	 */
	public static function groups()
	{
		return [
			'Account' => [
				'Custom OTP'                      => 'Customer requests a login / verification OTP',
				'Admin Registration Notification' => 'A new customer registers (sent to the admin)',
				'Profile Approve'                 => 'Admin approves a customer profile',
				'Profile Pending'                 => 'Admin moves an approved profile back to pending',
				'Profile Reject'                  => 'Admin rejects a customer profile',
				'Profile Blocked'                 => 'Admin blocks a customer',
				'Profile UnBlock'                 => 'Admin unblocks a customer',
				'Profile Deleted'                 => 'Admin deletes a customer',
			],
			'Points' => [
				'Add Points'    => 'Points are credited (QR scan or by admin)',
				'Redeem Points' => 'Customer submits a redemption request',
			],
			'Redemption' => [
				'Redemption Request Received' => 'A carpenter submits a product redemption request from the app (one message per request, all products listed)',
				'Redemption Approval'   => 'Admin approves a redemption request',
				'Redemption Processing' => 'Redemption is dispatched / marked processing',
				'Redemption Delivered'  => 'Dealer hands the product to the carpenter (WhatsApp code verified, or admin marks delivered)',
				'Redemption Cancelled'  => 'Admin rejects a redemption request',
			],
		];
	}

	/**
	 * Flat event => description map.
	 */
	public static function all()
	{
		$flat = array();
		foreach (self::groups() as $events) {
			$flat = array_merge($flat, $events);
		}
		return $flat;
	}

	public static function exists($event)
	{
		return array_key_exists($event, self::all());
	}

	public static function description($event)
	{
		$all = self::all();
		return isset($all[$event]) ? $all[$event] : null;
	}

	/**
	 * Events that are normally about something the admin has to act on, so the
	 * create screen suggests "Admin" as the recipient. Any event can still be
	 * configured for any recipient.
	 */
	public static function adminEvents()
	{
		return ['Admin Registration Notification', 'Redemption Request Received'];
	}
}
