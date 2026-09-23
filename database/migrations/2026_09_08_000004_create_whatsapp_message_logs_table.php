<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappMessageLogsTable extends Migration
{
    public function up()
    {
        Schema::create('whatsapp_message_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('wamid', 191)->nullable()->index();
            $table->string('mobileno', 20)->nullable()->index();
            $table->string('template_name', 255)->nullable();
            $table->unsignedInteger('message_id')->nullable();
            $table->longText('payload')->nullable();
            $table->longText('response')->nullable();
            // sent -> delivered -> read, or failed. Driven by the Celitix webhook.
            $table->string('status', 30)->default('pending');
            $table->text('error')->nullable();
            $table->timestamp('status_at')->nullable();
            $table->string('ip', 255)->nullable();
            $table->unsignedInteger('site_id')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_message_logs');
    }
}
