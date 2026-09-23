<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappBotSessionsTable extends Migration
{
    public function up()
    {
        Schema::create('whatsapp_bot_sessions', function (Blueprint $table) {
            $table->increments('id');

            // Dealer's number, last 10 digits (see WhatsappBotService::normaliseLast10) -
            // one open conversation per dealer at a time.
            $table->string('dealer_mobile', 10)->unique();

            // awaiting_carpenter_mobile -> awaiting_code -> (row deleted on success)
            $table->string('state', 40);
            $table->unsignedInteger('carpenter_user_id')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_bot_sessions');
    }
}
