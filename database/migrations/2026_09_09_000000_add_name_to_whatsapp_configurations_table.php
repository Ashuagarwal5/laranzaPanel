<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddNameToWhatsappConfigurationsTable extends Migration
{
    public function up()
    {
        Schema::table('whatsapp_configurations', function (Blueprint $table) {
            // The admin's own label for this mapping ("Redemption Request
            // Approved"); message_id stays as the system event that fires it.
            $table->string('name', 255)->nullable()->after('id');
        });

        DB::statement('UPDATE '.DB::getTablePrefix().'whatsapp_configurations c
            JOIN '.DB::getTablePrefix().'messages_info m ON m.id = c.message_id
            SET c.name = m.title WHERE c.name IS NULL');
    }

    public function down()
    {
        Schema::table('whatsapp_configurations', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
}
