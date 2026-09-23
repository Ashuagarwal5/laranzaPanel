<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTargetScreenToNotificationsTable extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Snapshotted from messages_info.app_target_screen at creation
            // time, same reasoning as why title/description are already
            // resolved text rather than re-read from the message later.
            $table->string('target_screen', 40)->nullable()->after('description');
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('target_screen');
        });
    }
}
