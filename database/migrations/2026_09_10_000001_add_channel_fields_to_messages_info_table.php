<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddChannelFieldsToMessagesInfoTable extends Migration
{
    /**
     * Tokens the old gateway substituted inside the message text, and the
     * registry token that now carries the same value.
     */
    protected $legacyTokens = [
        '{user}'          => '{user_name}',
        '{reward_points}' => '{points}',
        '{otp}'           => '{otp}',
        '{mobileno}'      => '{mobileno}',
    ];

    public function up()
    {
        Schema::table('messages_info', function (Blueprint $table) {
            $table->string('sms_template_id', 100)->nullable()->after('mode');
            $table->string('sms_sender_id', 20)->nullable()->after('sms_template_id');
            // DLT text with {#var#} placeholders, exactly as registered.
            $table->text('sms_message')->nullable()->after('sms_sender_id');
            // {"1":"{user_name}","2":"{points}"} - placeholder position => token.
            $table->longText('sms_variable_mapping')->nullable()->after('sms_message');
            $table->text('app_message')->nullable()->after('sms_variable_mapping');
        });

        $senderId = DB::table('website_settings')->value('sender_id');
        $pattern  = '/' . implode('|', array_map('preg_quote', array_keys($this->legacyTokens))) . '/';

        foreach (DB::table('messages_info')->get() as $row) {
            $text = (string) $row->message;

            // The old text said "Dear {user}" where DLT registered "Dear {#var#}",
            // so each legacy token becomes the next {#var#} and is mapped to its
            // registry token - the existing DLT templates keep working unchanged.
            $mapping  = [];
            $position = 0;
            $smsText  = preg_replace_callback($pattern, function ($match) use (&$mapping, &$position) {
                $mapping[++$position] = $this->legacyTokens[$match[0]];
                return '{#var#}';
            }, $text);

            // Modes: SMS carries over (it now goes through Celitix). WhatsApp is on
            // only where an active mapping exists. App notifications were never
            // actually sent before, so they stay off until an admin enables them.
            $modes = array_map('trim', explode(',', (string) $row->mode));
            $modes = array_intersect($modes, ['Sms']);
            $hasWhatsapp = DB::table('whatsapp_configurations')
                ->where('message_id', $row->id)
                ->where('status', 'Active')
                ->whereNull('deleted_at')
                ->exists();
            if ($hasWhatsapp) {
                $modes[] = 'WhatsApp';
            }

            DB::table('messages_info')->where('id', $row->id)->update([
                'sms_template_id'      => $row->template_id,
                'sms_sender_id'        => $senderId,
                'sms_message'          => $text === '' ? null : $smsText,
                'sms_variable_mapping' => json_encode($mapping, JSON_FORCE_OBJECT),
                'app_message'          => $text === '' ? null : strtr($text, $this->legacyTokens),
                'mode'                 => implode(',', array_values($modes)),
            ]);
        }
    }

    public function down()
    {
        // mode is not restored: the pre-migration value mixed live and dead channels.
        Schema::table('messages_info', function (Blueprint $table) {
            $table->dropColumn(['sms_template_id', 'sms_sender_id', 'sms_message', 'sms_variable_mapping', 'app_message']);
        });
    }
}
