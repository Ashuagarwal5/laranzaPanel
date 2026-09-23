<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveStatusFromRewardCatalogCategoriesTable extends Migration
{
    /**
     * Categories are no longer switched on and off - a category exists or it is
     * deleted. Product visibility is still driven by tbl_reward_products.status.
     */
    public function up()
    {
        if (Schema::hasColumn('reward_catalog_categories', 'status')) {
            Schema::table('reward_catalog_categories', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }

    public function down()
    {
        if (!Schema::hasColumn('reward_catalog_categories', 'status')) {
            Schema::table('reward_catalog_categories', function (Blueprint $table) {
                $table->boolean('status')->default(1)->after('sort_order');
            });
        }
    }
}
