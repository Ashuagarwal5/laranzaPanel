<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Eloquent {

       use SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'messages_info';



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

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	/**
	* To allow soft deletes
	*/

    protected $dates = ['created_at'];

 function __construct()
    {
        parent::__construct();
      	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip(),'site_id'=>config('constants.siteinfo.site_id'));

    }

	/**
	 * The channels a message can go out on: stored value => label.
	 */
	public static function channels()
	{
		return ['Sms' => 'SMS', 'WhatsApp' => 'WhatsApp', 'AppNotification' => 'App Notification'];
	}

	public static function modeBadge($mode)
	{
		$colors = ['Sms' => 'info', 'WhatsApp' => 'success', 'AppNotification' => 'primary'];
		$labels = self::channels();
		return '<span class="label label-' . $colors[$mode] . '">' . $labels[$mode] . '</span>';
	}

	/**
	 * Who a message goes to: stored value => label. One event may have one
	 * message per recipient, each with its own templates.
	 */
	public static function recipients()
	{
		return ['user' => 'Carpenter / Customer', 'admin' => 'Admin', 'dealer' => 'Dealer'];
	}

	public static function recipientLabel($recipient)
	{
		$labels = self::recipients();
		return isset($labels[$recipient]) ? $labels[$recipient] : $labels['user'];
	}

	/**
	 * Enabled channels, in display order.
	 */
	public function modes()
	{
		$saved = array_map('trim', explode(',', (string) $this->mode));
		return array_values(array_intersect(array_keys(self::channels()), $saved));
	}

	public function hasMode($mode)
	{
		return in_array($mode, $this->modes());
	}

	/**
	 * SMS variable position => token, e.g. [1 => '{user_name}', 2 => '{points}'].
	 */
	public function smsMapping()
	{
		$mapping = json_decode((string) $this->sms_variable_mapping, true);
		return is_array($mapping) ? $mapping : array();
	}

	/**
	 * The WhatsApp template mapping for this event, active one first.
	 */
	public function whatsappConfiguration()
	{
		return WhatsappConfiguration::where('message_id', $this->id)
			->orderByRaw("status = 'Active' DESC")
			->orderBy('id', 'desc')
			->first();
	}

 public static function getMessage($params)
	{
	   try {
			 $data =   Message::select('id','title','message','created_at')
			 ->orderBy('id', 'DESC')
			->paginate(10);

			$result['data'] = $data;
			$status = 'success';
			if(count($data)>0)
			{
			   $msg ="Detail Found";
			}
			else
			{
			   $msg = 'No Detail Found';
			}
		}catch (\Illuminate\Database\QueryException $e){
			   $status = 'error';
			   $msg	= "Error IN Query : ".$e->getMessage();
		  } catch (PDOException $e) {
			   $status = 'error';
			   $msg	= "Error IN Query : ".$e->getMessage();
		 } catch (\Exception $e) {
			$status = 'error';
			$msg	= $e->getMessage();
	   }

	  if($status == 'success')
	  $statusType = true;
	  else
	  $statusType =  false;
	  $result['success'] = $statusType;
	   $result['message'] = $msg;

	  return $result;
   }

}
