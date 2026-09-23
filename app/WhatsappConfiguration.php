<?php namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as Eloquent;

class WhatsappConfiguration extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'whatsapp_configurations';

	protected $primaryKey = 'id';
	protected $fillable = [];
	protected $guarded = ['id'];

	protected $hidden = [''];

	/**
	* To allow soft deletes
	*/
	use SoftDeletes;

	protected $dates = ['deleted_at'];

	function __construct()
	{
		parent::__construct();
		$this->attributes = array('ip' => Helpers\Thumbnail::getclientip(), 'site_id' => config('constants.siteinfo.site_id'));
	}

	/**
	 * The app event (messages_info row) this configuration belongs to.
	 */
	public function message()
	{
		return $this->belongsTo(Message::class, 'message_id');
	}

	/**
	 * The approved template this configuration points at.
	 */
	public function template()
	{
		return WhatsappTemplate::where('template_name', $this->template_name)
			->where('language', $this->language)
			->first();
	}

	/**
	 * Placeholder => token mapping, e.g. ['user_name' => '{user_name}'].
	 */
	public function mapping()
	{
		$mapping = json_decode((string) $this->variable_mapping, true);
		return is_array($mapping) ? $mapping : array();
	}
}
