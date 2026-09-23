<?php namespace App\Services;

use App\WebsiteSetting;

/**
 * The variables an admin can put into a message, shared by every channel.
 *
 * - SMS: each DLT {#var#} placeholder is mapped to one of these tokens.
 * - WhatsApp: each template placeholder ({{1}}, {{user_name}}) is mapped to one.
 * - App Notification: tokens are written straight into the text ({user_name}).
 *
 * At send time every token is resolved from the triggering event's context.
 *
 * ---------------------------------------------------------------------------
 * TO ADD A NEW VARIABLE
 * ---------------------------------------------------------------------------
 *   1. Add "'{my_token}' => 'Readable Label'" to the right group in groups().
 *   2. Add "'{my_token}' => <where the value comes from>" in resolve().
 * That is all - the admin dropdowns and the send path all read from here.
 *
 * If the value lives on a record the trigger does not currently pass, also add
 * it to the $context array at the getSendMessage() call site (see
 * docs/messaging-integration.md).
 */
class MessageTokens {

	/**
	 * Grouped token => label, for the mapping dropdown.
	 *
	 * The groups are display-only; every token is offered for every event,
	 * because one message may legitimately mix customer and order details.
	 * A token with no value for a given event resolves to an empty string.
	 */
	public static function groups()
	{
		return [
			'Customer' => [
				'{user_name}'      => 'Customer Full Name',
				'{first_name}'     => 'Customer First Name',
				'{last_name}'      => 'Customer Last Name',
				'{mobileno}'       => 'Customer Mobile Number',
				'{email}'          => 'Customer Email',
				'{customer_id}'    => 'Customer Id',
				'{employee_id}'    => 'Customer Employee Id',
				'{address}'        => 'Customer Address',
				'{city}'           => 'Customer City',
				'{state}'          => 'Customer State',
				'{pincode}'        => 'Customer Pincode',
				'{referral_code}'  => 'Customer Referral Code',
				'{profile_status}' => 'Customer Profile Status',
				'{total_points}'   => 'Customer Total Points Balance',
				'{otp}'            => 'OTP / Verification Code',
			],
			'Points' => [
				'{points}'          => 'Points In This Transaction',
				'{points_spent}'    => 'Points Spent On Redemption',
				'{points_per_unit}' => 'Points Per Unit',
			],
			'Redemption Request' => [
				'{request_id}'      => 'Redemption Request Id (whole request)',
				'{claim_id}'        => 'Redemption Claim Id (one product)',
				'{claim_status}'    => 'Redemption Status',
				'{request_date}'    => 'Request Date',
				'{approval_date}'   => 'Approval Date',
				'{dispatch_date}'   => 'Dispatch Date',
				'{delivery_date}'   => 'Delivery Date',
				'{reject_reason}'   => 'Rejection Reason',
				'{admin_remark}'    => 'Admin Remark',
				'{delivery_remark}' => 'Delivery Remark',
				'{request_source}'  => 'Requested From (App / Admin)',
				'{redemption_code}' => 'Dealer Pickup Code',
			],
			'Product' => [
				'{product_name}'  => 'Product Name',
				'{request_products}'     => 'All Products In The Request (e.g. Hand Shower x2, Drawer)',
				'{request_total_points}' => 'Total Points Of The Request',
				'{quantity}'      => 'Quantity',
				'{courier_name}'  => 'Courier Name',
				'{tracking_number}' => 'Tracking Number',
			],
			'Dealer' => [
				'{dealer_name}'         => 'Dealer Name',
				'{dealer_mobile}'       => 'Dealer Mobile Number',
				'{dealer_address}'      => 'Dealer Address',
				'{dealer_city}'         => 'Dealer City',
				'{dealer_pincode}'      => 'Dealer Pincode',
				'{dealer_full_address}' => 'Dealer Full Address (one line)',
			],
			'Company' => [
				'{site_name}'       => 'Company Name',
				'{support_email}'   => 'Support Email',
				'{contact_no}'      => 'Company Contact Number',
				'{company_address}' => 'Company Address',
				'{today}'           => "Today's Date",
				'{current_time}'    => 'Current Time',
			],
		];
	}

	/**
	 * Flat token => label map.
	 */
	public static function all()
	{
		$flat = array();
		foreach (self::groups() as $tokens) {
			foreach ($tokens as $token => $label) {
				$flat[$token] = $label;
			}
		}
		return $flat;
	}

	public static function isToken($value)
	{
		return array_key_exists($value, self::all());
	}

	/**
	 * Resolve every token to a concrete string for one send.
	 *
	 * $context carries whatever the trigger knows - typically ['claim' => $claim]
	 * for the redemption events - plus the points figure the caller passes.
	 */
	public static function resolve($userData = null, $point = null, array $context = array())
	{
		$claim    = isset($context['claim']) ? $context['claim'] : null;
		// Every product line of one app request; just the one claim otherwise.
		$claims   = !empty($context['claims']) ? $context['claims'] : ($claim ? [$claim] : []);
		$settings = WebsiteSetting::getGeneralSetting();

		$values = [
			// --- Customer -------------------------------------------------
			'{user_name}'      => self::pick($userData, 'full_name', self::fullName($userData)),
			'{first_name}'     => self::pick($userData, 'first_name'),
			'{last_name}'      => self::pick($userData, 'last_name'),
			'{mobileno}'       => self::pick($userData, 'mobileno'),
			'{email}'          => self::pick($userData, 'email'),
			'{customer_id}'    => self::pick($userData, 'id'),
			'{employee_id}'    => self::pick($userData, 'employee_id'),
			'{address}'        => self::join([self::pick($userData, 'address'), self::pick($userData, 'address_line2')]),
			'{city}'           => self::pick($userData, 'city'),
			'{state}'          => self::pick($userData, 'state'),
			'{pincode}'        => self::pick($userData, 'pincode'),
			'{referral_code}'  => self::pick($userData, 'referral_code'),
			'{profile_status}' => self::pick($userData, 'profile_status'),
			'{total_points}'   => self::pick($userData, 'total_customer_points'),
			'{otp}'            => self::pick($userData, 'verification_code'),

			// --- Points ---------------------------------------------------
			'{points}'          => $point,
			'{points_spent}'    => self::pick($claim, 'points_spent', $point),
			'{points_per_unit}' => self::pick($claim, 'points_per_unit'),

			// --- Redemption request --------------------------------------
			'{request_id}'      => self::pick($claim, 'claim_group_id', self::pick($claim, 'id')),
			'{claim_id}'        => self::pick($claim, 'id'),
			'{claim_status}'    => self::pick($claim, 'status'),
			'{request_date}'    => self::date($claim ? $claim->created_at : null),
			// updated_at is stamped by the status change that fired this send.
			'{approval_date}'   => self::date($claim ? $claim->updated_at : null),
			'{dispatch_date}'   => self::date($claim ? $claim->dispatched_at : null),
			'{delivery_date}'   => self::date($claim ? $claim->delivered_at : null),
			'{reject_reason}'   => self::pick($claim, 'admin_remark'),
			'{admin_remark}'    => self::pick($claim, 'admin_remark'),
			'{delivery_remark}' => self::pick($claim, 'delivered_remark'),
			'{request_source}'  => self::pick($claim, 'added_from'),
			'{redemption_code}' => self::pick($claim, 'redemption_code'),

			// --- Product --------------------------------------------------
			'{product_name}'    => self::pick($claim, 'product_name'),
			'{request_products}'     => self::products($claims),
			'{request_total_points}' => $claims ? array_sum(array_map(function ($c) { return (int) $c->points_spent; }, $claims)) : null,
			'{quantity}'        => self::pick($claim, 'quantity'),
			'{courier_name}'    => self::pick($claim, 'courier_name'),
			'{tracking_number}' => self::pick($claim, 'tracking_number'),

			// --- Dealer ---------------------------------------------------
			'{dealer_name}'    => self::pick($claim, 'dealer_name'),
			'{dealer_mobile}'  => self::pick($claim, 'dealer_mobile'),
			'{dealer_address}' => self::pick($claim, 'dealer_address'),
			'{dealer_city}'    => self::pick($claim, 'dealer_city'),
			'{dealer_pincode}' => self::pick($claim, 'dealer_pincode'),
			'{dealer_full_address}' => self::join([
				self::pick($claim, 'dealer_address'),
				self::pick($claim, 'dealer_city'),
				self::pick($claim, 'dealer_pincode'),
			]),

			// --- Company --------------------------------------------------
			'{site_name}'       => self::pick($settings, 'site_name'),
			'{support_email}'   => self::pick($settings, 'customer_support_email', self::pick($settings, 'admin_email')),
			'{contact_no}'      => self::pick($settings, 'contact_no'),
			'{company_address}' => self::pick($settings, 'address'),
			'{today}'           => date('d M Y'),
			'{current_time}'    => date('h:i A'),
		];

		// Meta rejects a parameter that is null, so every token sends as a
		// string - an unavailable one simply goes out empty.
		foreach ($values as $token => $value) {
			$values[$token] = ($value === null || $value === '') ? '' : (string) $value;
		}

		// A caller may override or add values directly.
		if (!empty($context['values']) && is_array($context['values'])) {
			foreach ($context['values'] as $token => $value) {
				$values[$token] = (string) $value;
			}
		}

		return $values;
	}

	protected static function pick($object, $property, $default = null)
	{
		if (is_object($object) && isset($object->{$property}) && $object->{$property} !== '') {
			return $object->{$property};
		}
		return $default;
	}

	/**
	 * Join the parts of an address, skipping the blanks.
	 */
	protected static function join(array $parts)
	{
		$parts = array_filter(array_map('trim', array_map('strval', $parts)), 'strlen');
		return $parts ? implode(', ', $parts) : null;
	}

	/**
	 * "Hand Shower x2, Drawer" - the product lines of one request.
	 */
	protected static function products(array $claims)
	{
		$names = array();
		foreach ($claims as $claim) {
			$name = self::pick($claim, 'product_name');
			if ($name) {
				$names[] = $name.((int) self::pick($claim, 'quantity', 1) > 1 ? ' x'.$claim->quantity : '');
			}
		}
		return $names ? implode(', ', $names) : null;
	}

	protected static function fullName($userData)
	{
		$name = self::join([self::pick($userData, 'first_name'), self::pick($userData, 'last_name')]);
		return $name ? str_replace(', ', ' ', $name) : 'Customer';
	}

	protected static function date($value)
	{
		if (empty($value)) {
			return null;
		}
		$stamp = is_numeric($value) ? (int) $value : strtotime((string) $value);
		return $stamp ? date('d M Y', $stamp) : null;
	}
}
