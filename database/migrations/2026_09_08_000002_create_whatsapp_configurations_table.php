<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappConfigurationsTable extends Migration
{
    public function up()
    {
        Schema::create('whatsapp_configurations', function (Blueprint $table) {
            $table->increments('id');
            // Binds one app event (a row of messages_info, e.g. "Redeem Points")
            // to the approved WhatsApp template that should carry it.
            $table->unsignedInteger('message_id');
            $table->string('template_name', 255);
            $table->string('language', 20)->default('en');
            $table->string('category', 50)->nullable();
            // {"1":"{user}","2":"{reward_points}"} - template variable position
            // mapped to a system token or a static string.
            $table->longText('variable_mapping')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->string('ip', 255)->nullable();
            $table->unsignedInteger('site_id')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_configurations');
    }
}
