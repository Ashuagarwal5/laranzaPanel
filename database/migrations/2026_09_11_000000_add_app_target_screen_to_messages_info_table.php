<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAppTargetScreenToMessagesInfoTable extends Migration
{
    public function up()
    {
        Schema::table('messages_info', function (Blueprint $table) {
            // Which app screen a tap on this event's push notification opens.
            // One of the fixed keys in App\Services\PushScreens - null means
            // the notification just opens the app.
            $table->string('app_target_screen', 40)->nullable()->after('app_message');
        });
    }

    public function down()
    {
        Schema::table('messages_info', function (Blueprint $table) {
            $table->dropColumn('app_target_screen');
        });
    }
}
