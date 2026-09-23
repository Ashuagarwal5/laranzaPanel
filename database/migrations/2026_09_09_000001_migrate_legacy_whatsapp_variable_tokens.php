<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MigrateLegacyWhatsappVariableTokens extends Migration
{
    /**
     * The first token set was {user}/{reward_points}; the registry now names
     * them {user_name}/{points} alongside product and dealer tokens. Existing
     * mappings still hold the old names, which would send as literal text.
     */
    protected $map = [
        '{user}'          => '{user_name}',
        '{reward_points}' => '{points}',
    ];

    public function up()
    {
        $rows = DB::table('whatsapp_configurations')->select('id', 'variable_mapping')->get();
        foreach ($rows as $row) {
            $mapping = json_decode((string) $row->variable_mapping, true);
            if (!is_array($mapping)) {
                continue;
            }
            $changed = false;
            foreach ($mapping as $placeholder => $token) {
                if (isset($this->map[$token])) {
                    $mapping[$placeholder] = $this->map[$token];
                    $changed = true;
                }
            }
            if ($changed) {
                DB::table('whatsapp_configurations')
                    ->where('id', $row->id)
                    ->update(['variable_mapping' => json_encode($mapping)]);
            }
        }
    }

    public function down()
    {
        $rows = DB::table('whatsapp_configurations')->select('id', 'variable_mapping')->get();
        $reverse = array_flip($this->map);
        foreach ($rows as $row) {
            $mapping = json_decode((string) $row->variable_mapping, true);
            if (!is_array($mapping)) {
                continue;
            }
            foreach ($mapping as $placeholder => $token) {
                if (isset($reverse[$token])) {
                    $mapping[$placeholder] = $reverse[$token];
                }
            }
            DB::table('whatsapp_configurations')->where('id', $row->id)
                ->update(['variable_mapping' => json_encode($mapping)]);
        }
    }
}
