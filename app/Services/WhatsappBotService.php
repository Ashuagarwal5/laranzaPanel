<?php namespace App\Services;

use App\Dealer;
use App\RewardClaim;
use App\User;
use App\WhatsappBotSession;

/**
 * The dealer-side WhatsApp pickup flow.
 *
 * A dealer messages the WABA number to hand a reward product to a carpenter:
 *
 *   dealer: start
 *   bot:    You have 2 pending pickup(s). Please send the carpenter's registered mobile number.
 *   dealer: 9876543210
 *   bot:    Ashu has the following pending pickup(s):
 *           - Hand Shower
 *           - Drawer x2
 *
 *           Please send me the 6-digit code.
 *   dealer: 483920
 *   bot:    Code verified! Please hand over <product> to the carpenter.
 *
 * The pickup code is generated when the admin marks a claim Dispatched (the
 * product has physically reached the dealer), not on approval - see
 * RewardClaimController::dispatchToDealer().
 *
 * A number that is not a registered dealer gets no reply at all (see
 * handleIncoming()) - only a verified dealer ever hears back from the bot.
 *
 * Conversation state (which carpenter was picked) lives in
 * whatsapp_bot_sessions, keyed by the dealer's number, because the final code
 * has to be checked against THAT carpenter's claim specifically - not just
 * any claim the dealer happens to have.
 */
class WhatsappBotService {

	const STATE_AWAITING_CARPENTER = 'awaiting_carpenter_mobile';
	const STATE_AWAITING_CODE      = 'awaiting_code';

	/**
	 * Entry point for one inbound WhatsApp text message.
	 *
	 * @param string $from Sender's number as Celitix/Meta send it (digits, country code included, e.g. 91XXXXXXXXXX)
	 * @param string $text Raw message body
	 */
	public static function handleIncoming($from, $text)
	{
		$dealerMobile = self::last10($from);
		$dealerUserId = Dealer::checkDealerByMobileNumber($dealerMobile);

		// Nothing is ever sent to a number that is not a verified dealer -
		// no "you are not registered" reply either.
		if (empty($dealerUserId)) {
			return;
		}

		$body   = trim((string) $text);
		$lower  = strtolower($body);
		$digits = preg_replace('/[^0-9]/', '', $body);

		if ($lower === 'start') {
			self::handleStart($from, $dealerMobile, $dealerUserId);
			return;
		}

		if ($lower === 'cancel') {
			self::handleCancel($from, $dealerMobile);
			return;
		}

		$session = WhatsappBotSession::where('dealer_mobile', $dealerMobile)->first();

		if ($session && $session->state === self::STATE_AWAITING_CARPENTER) {
			if (strlen($digits) >= 10) {
				self::handleCarpenterMobile($from, $session, $dealerUserId, $digits);
			} else {
				CelitixWhatsapp::sendText($from, "That doesn't look like a valid mobile number. Please send the carpenter's 10-digit mobile number, or send *CANCEL*.");
			}
			return;
		}

		if ($session && $session->state === self::STATE_AWAITING_CODE) {
			if (strlen($digits) === 6) {
				self::handleCode($from, $session, $dealerUserId, $digits);
			} elseif (strlen($digits) >= 10) {
				// Looks like they're trying to pick a different carpenter mid-flow
				// instead of entering the code - a plain number here would be
				// ambiguous, so send them through START instead of guessing.
				CelitixWhatsapp::sendText($from, "You're already picking up for the carpenter you selected. If you want to restart and pick a different carpenter, please send *START* (or *CANCEL*).");
			} else {
				CelitixWhatsapp::sendText($from, "That doesn't look like a valid code. Please send the 6-digit code the carpenter gave you, or send *CANCEL*.");
			}
			return;
		}

		// No conversation in progress and the message wasn't START/CANCEL.
		CelitixWhatsapp::sendText($from, 'Send *START* to begin a product pickup.');
	}

	protected static function handleCancel($from, $dealerMobile)
	{
		WhatsappBotSession::where('dealer_mobile', $dealerMobile)->delete();
		CelitixWhatsapp::sendText($from, 'Cancelled. If you want to start again, send *START*.');
	}

	protected static function handleStart($from, $dealerMobile, $dealerUserId)
	{
		$pending = self::pendingClaims($dealerUserId)->count();

		if ($pending < 1) {
			WhatsappBotSession::where('dealer_mobile', $dealerMobile)->delete();
			CelitixWhatsapp::sendText($from, 'You have no pending product pickups right now.');
			return;
		}

		WhatsappBotSession::updateOrCreate(
			['dealer_mobile' => $dealerMobile],
			['state' => self::STATE_AWAITING_CARPENTER, 'carpenter_user_id' => null]
		);

		$what = $pending == 1 ? '1 pending pickup' : $pending.' pending pickups';
		CelitixWhatsapp::sendText($from, "You have {$what}. Please send the carpenter's registered mobile number. (Send *CANCEL* to stop.)");
	}

	protected static function handleCarpenterMobile($from, WhatsappBotSession $session, $dealerUserId, $mobileDigits)
	{
		$last10    = substr($mobileDigits, -10);
		$carpenter = User::where('mobileno', $last10)->where('dealer_id', $dealerUserId)->first();

		if (empty($carpenter)) {
			CelitixWhatsapp::sendText($from, 'This mobile number is not a carpenter registered under you. Please check and send it again, or send *CANCEL*.');
			return;
		}

		$activeClaims = self::pendingClaims($dealerUserId)->where('user_id', $carpenter->id)->get();

		if ($activeClaims->isEmpty()) {
			CelitixWhatsapp::sendText($from, 'This carpenter has no pending pickup with a code generated. Please check and send another number, or send *CANCEL*.');
			return;
		}

		$session->state             = self::STATE_AWAITING_CODE;
		$session->carpenter_user_id = $carpenter->id;
		$session->save();

		$name = $carpenter->full_name ?: 'The carpenter';
		$lines = $activeClaims->map(function ($claim) {
			return '- '.$claim->product_name.($claim->quantity > 1 ? ' x'.$claim->quantity : '');
		})->implode("\n");

		CelitixWhatsapp::sendText($from, "{$name} has the following pending pickup(s):\n{$lines}\n\nPlease send me the 6-digit code. (Send *CANCEL* to stop.)");
	}

	protected static function handleCode($from, WhatsappBotSession $session, $dealerUserId, $code)
	{
		$claim = self::pendingClaims($dealerUserId)
			->where('user_id', $session->carpenter_user_id)
			->where('redemption_code', $code)
			->first();

		if (empty($claim)) {
			CelitixWhatsapp::sendText($from, 'This code is invalid. Please enter a valid code, or send *CANCEL*.');
			return;
		}

		$claim->markDelivered('Verified via WhatsApp bot by dealer.', true);
		$session->delete();

		$what = $claim->product_name.($claim->quantity > 1 ? ' (x'.$claim->quantity.')' : '');
		CelitixWhatsapp::sendText($from, "Code verified! Please give {$what} to the carpenter. Thank you.");
	}

	/**
	 * Claims ready for pickup: Dispatched (the product has physically
	 * reached the dealer - which is also when the code is generated) with a
	 * code that has not been used yet.
	 */
	protected static function pendingClaims($dealerUserId)
	{
		return RewardClaim::where('dealer_id', $dealerUserId)
			->where('status', 'Dispatched')
			->whereNotNull('redemption_code')
			->whereNull('code_verified_at');
	}

	/**
	 * dealers.mobile_no / users.mobileno are stored as plain 10-digit
	 * numbers, while the inbound WhatsApp sender carries the country code -
	 * compare on the last 10 digits everywhere.
	 */
	protected static function last10($mobile)
	{
		return substr(preg_replace('/[^0-9]/', '', (string) $mobile), -10);
	}
}
