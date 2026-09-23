<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSmsApiKeyToWebsiteSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('website_settings', function (Blueprint $table) {
            // Celitix issues SMS credentials separately from WABA; the WhatsApp
            // key is rejected by the SMS endpoint (InvalidUseridPassword).
            $table->string('sms_api_key', 255)->nullable()->after('sms_entity_id');
        });
    }

    public function down()
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn('sms_api_key');
        });
    }
}
