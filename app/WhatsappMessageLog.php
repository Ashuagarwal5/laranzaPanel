<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class WhatsappMessageLog extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'whatsapp_message_logs';

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
