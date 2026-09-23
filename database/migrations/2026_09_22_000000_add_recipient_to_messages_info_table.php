<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddRecipientToMessagesInfoTable extends Migration
{
    public function up()
    {
        Schema::table('messages_info', function (Blueprint $table) {
            // Who receives this message: user (the carpenter/customer), admin or
            // dealer. One event may have one message per recipient - see
            // App\Message::recipients().
            $table->string('recipient', 20)->default('user')->after('title');
        });

        // The only event that went to the admin before recipients existed.
        DB::table('messages_info')
            ->where('title', 'Admin Registration Notification')
            ->update(['recipient' => 'admin']);
    }

    public function down()
    {
        Schema::table('messages_info', function (Blueprint $table) {
            $table->dropColumn('recipient');
        });
    }
}
