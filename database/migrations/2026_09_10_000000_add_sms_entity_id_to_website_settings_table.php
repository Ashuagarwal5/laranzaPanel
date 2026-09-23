<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSmsEntityIdToWebsiteSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('website_settings', function (Blueprint $table) {
            // DLT Principal Entity ID, sent with every Celitix SMS. The API key is
            // shared with WhatsApp (whatsaap_api_key); the old username/password/
            // sender_id columns belonged to the previous SMS gateway and are unused.
            $table->string('sms_entity_id', 50)->nullable()->after('waba_number');
        });
    }

    public function down()
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn('sms_entity_id');
        });
    }
}
