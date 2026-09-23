<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClaimGroupToRewardClaimsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('tbl_reward_claims', 'claim_group_id')) {
            Schema::table('tbl_reward_claims', function (Blueprint $table) {
                $table->string('claim_group_id', 40)
                    ->nullable()
                    ->after('id');

                $table->index(
                    'claim_group_id',
                    'reward_claims_claim_group_id_index'
                );
            });
        }

        if (!Schema::hasColumn('tbl_reward_claims', 'quantity')) {
            Schema::table('tbl_reward_claims', function (Blueprint $table) {
                $table->unsignedInteger('quantity')
                    ->default(1)
                    ->after('product_name');
            });
        }

        if (!Schema::hasColumn('tbl_reward_claims', 'points_per_unit')) {
            Schema::table('tbl_reward_claims', function (Blueprint $table) {
                $table->integer('points_per_unit')
                    ->default(0)
                    ->after('quantity');
            });
        }
    }

    public function down()
    {
        // Leave empty for now because these columns already existed
        // in this database before this migration was registered.
    }
}