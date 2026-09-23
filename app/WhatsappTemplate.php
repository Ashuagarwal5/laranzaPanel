<?php namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as Eloquent;

class WhatsappTemplate extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'whatsapp_templates';

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
	 * Template categories offered by Meta / Celitix.
	 */
	public static function categories()
	{
		return ['MARKETING' => 'Marketing', 'UTILITY' => 'Utility', 'AUTHENTICATION' => 'Authentication'];
	}

	/**
	 * Approved templates, optionally narrowed to one category.
	 */
	public static function approved($category = null)
	{
		$query = self::where('approval_status', 'APPROVED');
		if (!empty($category)) {
			$query->where('category', $category);
		}
		return $query->orderBy('template_name', 'asc')->get();
	}

	/**
	 * Placeholders present in the header and body, in the order they appear.
	 *
	 * POSITIONAL templates give back ints (1, 2, 3) and NAMED ones give back
	 * strings, but either way the key is what sits inside the double braces.
	 */
	public function variablePositions()
	{
		preg_match_all('/\{\{\s*([A-Za-z0-9_]+)\s*\}\}/', (string) $this->header_text . ' ' . (string) $this->body_text, $matches);
		$keys = array();
		foreach ($matches[1] as $key) {
			$key = ctype_digit($key) ? (int) $key : $key;
			if (!in_array($key, $keys, true)) {
				$keys[] = $key;
			}
		}
		// Positional placeholders must be sent in numeric order, not text order.
		if (!empty($keys) && count(array_filter($keys, 'is_int')) === count($keys)) {
			sort($keys);
		}
		return $keys;
	}

	/**
	 * Header + body + footer with every {{n}} swapped for $values[n].
	 */
	public function preview(array $values = [])
	{
		$parts = array();
		if (!empty($this->header_text)) {
			$parts[] = $this->header_text;
		}
		if (!empty($this->body_text)) {
			$parts[] = $this->body_text;
		}
		if (!empty($this->footer_text)) {
			$parts[] = $this->footer_text;
		}
		$text = implode("\n\n", $parts);
		foreach ($values as $position => $value) {
			if ($value === null || $value === '') {
				continue;
			}
			$text = str_replace('{{' . $position . '}}', $value, $text);
		}
		return $text;
	}
}
