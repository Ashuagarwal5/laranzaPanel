<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddQuantityToRewardClaimsTable extends Migration
{
    public function up()
    {
        Schema::table('reward_claims', function (Blueprint $table) {
            // A carpenter may now claim several units of the same product in one go,
            // so points_spent is points_required * quantity instead of a single unit.
            $table->unsignedInteger('quantity')->default(1)->after('product_name');
            $table->integer('points_per_unit')->default(0)->after('quantity');
        });

        // Backfill the rows created while a claim was always a single unit.
        DB::statement('UPDATE '.DB::getTablePrefix().'reward_claims SET quantity = 1, points_per_unit = points_spent WHERE points_per_unit = 0');
    }

    public function down()
    {
        Schema::table('reward_claims', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'points_per_unit']);
        });
    }
}
