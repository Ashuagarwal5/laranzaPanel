<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsMessageLogsTable extends Migration
{
    public function up()
    {
        Schema::create('sms_message_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mobileno', 20)->nullable()->index();
            $table->unsignedInteger('message_id')->nullable();
            $table->string('sender_id', 20)->nullable();
            $table->string('template_id', 100)->nullable();
            $table->text('message')->nullable();
            // Request URL with the API key masked.
            $table->text('request')->nullable();
            $table->longText('response')->nullable();
            $table->string('status', 30)->default('pending');
            $table->string('provider_message_id', 100)->nullable()->index();
            $table->string('client_ref_id', 100)->nullable();
            $table->text('error')->nullable();
            $table->string('ip', 255)->nullable();
            $table->unsignedInteger('site_id')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sms_message_logs');
    }
}
