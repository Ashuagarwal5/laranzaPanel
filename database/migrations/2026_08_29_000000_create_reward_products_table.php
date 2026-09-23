<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRewardProductsTable extends Migration
{
    public function up()
    {
        Schema::create('reward_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku', 100)->nullable();
            $table->string('short_description', 500)->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('points_required')->default(0);
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('stock')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(1);
            $table->string('ip')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('category_id');
            $table->index('status');
            $table->index('points_required');

            $table->foreign('category_id')
                ->references('id')->on('reward_catalog_categories');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reward_products');
    }
}
