<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RemoveWhatsappConfigurationMenu extends Migration
{
    /**
     * WhatsApp configuration now lives in a tab of Add Message, so its own
     * sidebar entry goes away.
     */
    public function up()
    {
        DB::table('manager')
            ->where('page_link', 'whatsapp-configuration')
            ->update(['deleted_at' => date('Y-m-d H:i:s')]);
    }

    public function down()
    {
        DB::table('manager')
            ->where('page_link', 'whatsapp-configuration')
            ->update(['deleted_at' => null]);
    }
}
