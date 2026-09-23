<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFcmServiceAccountJsonToWebsiteSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('website_settings', function (Blueprint $table) {
            // The full Firebase service-account JSON (Project Settings ->
            // Service Accounts -> Generate new private key), used to
            // authenticate server-to-FCM v1 sends. A few KB, so text not
            // string.
            $table->text('fcm_service_account_json')->nullable()->after('fcm_icon_url');
        });
    }

    public function down()
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn('fcm_service_account_json');
        });
    }
}
