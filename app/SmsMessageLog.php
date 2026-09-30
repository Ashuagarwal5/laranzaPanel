<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SmsMessageLog extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'sms_message_logs';

	protected $primaryKey = 'id';
	protected $fillable = [];
	protected $guarded = ['id'];

	protected $hidden = [''];

	function __construct()
	{
		parent::__construct();
		$this->attributes = array('ip' => Helpers\Thumbnail::getclientip(), 'site_id' => config('constants.siteinfo.site_id'));
	}

	/**
	 * The table used to store the SMS text ("message"). It now stores the name of
	 * the event that sent it ("event") instead - the text held the OTP codes, which
	 * do not belong in a log. This adds the column and drops the old one, once, the
	 * first time the log is written or viewed, so it needs no manual database work.
	 */
	public static function ensureSchema()
	{
		static $done = false;
		if ($done) {
			return;
		}
		$done = true;

		try {
			if (!Schema::hasColumn('sms_message_logs', 'event')) {
				Schema::table('sms_message_logs', function ($table) {
					$table->string('event', 191)->nullable()->after('message_id');
				});
			}
			if (Schema::hasColumn('sms_message_logs', 'message')) {
				$prefix = DB::getTablePrefix();
				// Older rows: fill in the event name from the message they were sent for.
				DB::statement(
					'UPDATE '.$prefix.'sms_message_logs SET event = (SELECT title FROM '.$prefix.'messages_info WHERE '.$prefix.'messages_info.id = '.$prefix.'sms_message_logs.message_id) WHERE event IS NULL'
				);
				Schema::table('sms_message_logs', function ($table) {
					$table->dropColumn('message');
				});
			}
		} catch (\Throwable $e) {
			\Log::error('SmsMessageLog::ensureSchema failed: '.$e->getMessage());
		}
	}

	/**
	 * Keep only the newest rows: every 25th insert, delete everything more than
	 * $keep rows behind the row just written.
	 */
	public static function prune($currentId, $keep = 1000)
	{
		if ($currentId && $currentId % 25 === 0) {
			static::where('id', '<=', $currentId - $keep)->delete();
		}
	}
}
