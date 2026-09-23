<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class WhatsappBotSession extends Eloquent {

	protected $table = 'whatsapp_bot_sessions';
	protected $primaryKey = 'id';
	protected $fillable = [];
	protected $guarded = ['id'];
}
