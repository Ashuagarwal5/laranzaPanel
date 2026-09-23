<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use DB;
use Log;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\CelitixSms;
use App\Services\CelitixWhatsapp;
use App\Services\MessageTokens;

class SendMessage extends Eloquent {

       use SoftDeletes;

	/**
	 * The attributes to be fillable from the model.
	 *
	 * A dirty hack to allow fields to be fillable by calling empty fillable array
	 *
	 * @var array
	 */
	protected $primaryKey = 'id';
	protected $fillable = [];
	protected $guarded = ['id'];

    protected $dates = ['created_at'];

    function __construct()
    {
        parent::__construct();
      	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip(),'site_id'=>config('constants.siteinfo.site_id'));
    }

    /**
     * Send one event's messages over every channel enabled for them in Messages.
     *
     * $type is the event (messages_info.title). An event may have one message per
     * recipient (carpenter, admin, dealer); each goes to its own number. $id is
     * always the customer the event is about - the variables describe them even
     * when the message goes to the admin or the dealer. $totype is only the
     * fallback for a message saved without a recipient. $context carries extra
     * records for the variables, e.g. ['claim' => $claim].
     */
    public static function getSendMessage($type, $id = null, $point = null, $totype='user', array $context = array())
	{
        $messages = Message::where('title', $type)->get();
        if ($messages->isEmpty()) {
            return ['status' => 'error', 'success' => false, 'msgType' => 'error', 'msg' => 'No message is configured for "'.$type.'".'];
        }

        $userData = User::where('id', $id)->first();

        $sent = array();
        foreach ($messages as $datas) {
            $recipient = $datas->recipient ?: $totype;
            $channels  = array();
            try {
                list($to, $notifyUser) = self::recipientFor($recipient, $userData, $context);
            } catch (\Throwable $e) {
                Log::error('Message "'.$type.'" to '.$recipient.' failed: '.$e->getMessage());
                $sent[$recipient] = ['status' => 'error', 'msg' => $e->getMessage()];
                continue;
            }

            foreach ($datas->modes() as $mode) {
                try {
                    if ($mode == 'Sms') {
                        $channels[$mode] = self::sms($datas, $to, $userData, $point, $context);
                    } elseif ($mode == 'WhatsApp') {
                        $channels[$mode] = self::whatsappTemplate($datas, $to, $userData, $point, $context);
                    } elseif ($mode == 'AppNotification' && $notifyUser) {
                        $channels[$mode] = self::appNotification($datas, $userData, $point, $context, $notifyUser);
                    }
                } catch (\Throwable $e) {
                    // A failing channel must never break the action that fired it
                    // (a login, a claim approval...).
                    Log::error('Message "'.$type.'" to '.$recipient.' via '.$mode.' failed: '.$e->getMessage());
                    $channels[$mode] = ['status' => 'error', 'msg' => $e->getMessage()];
                }
            }
            $sent[$recipient] = $channels;
        }

        return ['status' => 'success', 'success' => true, 'msgType' => 'success', 'recipients' => $sent];
	}

    /**
     * The mobile number a recipient is reached on, and the user whose app
     * receives the notification (null for the admin, who has no app).
     */
    protected static function recipientFor($recipient, $userData, array $context)
    {
        if ($recipient == 'admin') {
            return [self::adminMobile(), null];
        }

        if ($recipient == 'dealer') {
            $claim    = isset($context['claim']) ? $context['claim'] : null;
            $dealerId = $claim && $claim->dealer_id ? $claim->dealer_id : ($userData ? $userData->dealer_id : null);
            $dealer   = $dealerId ? User::where('id', $dealerId)->first() : null;
            // The claim keeps the dealer counter's number as it was when claimed.
            $mobile   = $claim && $claim->dealer_mobile ? $claim->dealer_mobile : ($dealer ? $dealer->mobileno : null);
            return [$mobile, $dealer];
        }

        return [$userData ? $userData->mobileno : null, $userData];
    }

    protected static function adminMobile()
    {
        $settings = WebsiteSetting::getGeneralSetting();
        return ($settings && !empty($settings->admin_mobile_no)) ? $settings->admin_mobile_no : '9950448855';
    }

    /**
     * Turn a saved placeholder => token mapping into placeholder => value. A
     * value that is not a known token is a fixed string the admin typed.
     */
    protected static function mapValues(array $mapping, array $tokens)
    {
        $values = array();
        foreach ($mapping as $placeholder => $value) {
            $values[$placeholder] = array_key_exists($value, $tokens) ? $tokens[$value] : $value;
        }
        return $values;
    }

    /**
     * Send the event's DLT text through Celitix SMS, with each {#var#} filled in.
     */
    public static function sms($datas, $to, $userData = null, $point = null, array $context = array())
    {
        $tokens = MessageTokens::resolve($userData, $point, $context);
        $text   = CelitixSms::render($datas->sms_message, self::mapValues($datas->smsMapping(), $tokens));

        return CelitixSms::send($to, $text, $datas->sms_sender_id, $datas->sms_template_id, $datas->id);
    }

/**
 * Send a message event over WhatsApp as an approved Celitix template.
 *
 * The event has to be mapped to a template in the message's WhatsApp tab;
 * without an active mapping there is nothing Meta will accept, so the send is
 * skipped rather than failing the caller.
 */
public static function whatsappTemplate($datas, $to, $userData = null, $point = null, array $context = array())
{
    $config = WhatsappConfiguration::where('message_id', $datas->id)
        ->where('status', 'Active')
        ->orderBy('id', 'desc')
        ->first();
    if (empty($config)) {
        return ['status' => 'error', 'msg' => 'No active WhatsApp template is configured for "'.$datas->title.'".'];
    }

    $template = $config->template();
    if (empty($template)) {
        return ['status' => 'error', 'msg' => 'The configured WhatsApp template is missing. Sync templates again.'];
    }

    $tokens = MessageTokens::resolve($userData, $point, $context);

    return CelitixWhatsapp::sendTemplate($to, $template, self::mapValues($config->mapping(), $tokens), $datas->id);
}

/**
 * Queue an in-app notification. It shows in the app's notification list, and
 * the Nofification:cron command pushes it to the device of $notifyUser (the
 * customer by default, or e.g. their dealer).
 */
public static function appNotification($datas, $userData = null, $point = null, array $context = array(), $notifyUser = null)
{
    $notifyUser = $notifyUser ?: $userData;
    $text = trim(strtr((string) $datas->app_message, MessageTokens::resolve($userData, $point, $context)));
    if (empty($notifyUser) || $text === '') {
        return ['status' => 'error', 'msg' => 'No recipient or no notification text for "'.$datas->title.'".'];
    }

    $role = DB::table('role_users')
        ->join('roles', 'roles.id', '=', 'role_users.role_id')
        ->where('role_users.user_id', $notifyUser->id)
        ->value('roles.slug');

    $notification = new Notification();
    $notification->user_id       = $notifyUser->id;
    $notification->title         = $datas->title;
    $notification->description   = $text;
    $notification->type          = in_array($role, ['dealer', 'user', 'sales', 'interior']) ? $role : 'user';
    $notification->target_screen = $datas->app_target_screen;
    $notification->status        = 'Pending';
    $notification->save();

    return ['status' => 'success', 'notification_id' => $notification->id];
}

public static function whatsapp($post)
{
	$url="https://www.cp.bigtos.com/api/v1/sendmessage";
	$ch = curl_init( $url );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	$response = curl_exec($ch);
	curl_close($ch);
	# Print response.
	return $response;
}

public static function validateotp($otp){
dd($otp);
}
}
