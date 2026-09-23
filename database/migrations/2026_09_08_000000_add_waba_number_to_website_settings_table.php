<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWabaNumberToWebsiteSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('website_settings', function (Blueprint $table) {
            // The WABA (WhatsApp Business Account) number registered with Celitix,
            // used as the sender for every template message we push.
            $table->string('waba_number', 20)->nullable()->after('whatsaap_api_key');
        });
    }

    public function down()
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn('waba_number');
        });
    }
}
