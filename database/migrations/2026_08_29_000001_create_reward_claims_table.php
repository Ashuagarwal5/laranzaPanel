<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRewardClaimsTable extends Migration
{
    public function up()
    {
        Schema::create('reward_claims', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->unsignedBigInteger('reward_product_id')->nullable();
            $table->integer('customer_points_id')->nullable();

            // Snapshot of the product at claim time - the product may be edited or removed later.
            $table->string('product_name');
            $table->integer('points_spent');

            // The product is shipped to the carpenter's registered dealer, who hands it
            // over at the counter. dealer_id is the dealer's LOGIN user id (tbl_users.id),
            // which is what tbl_users.dealer_id stores - not tbl_dealers.id.
            $table->integer('dealer_id')->nullable();
            $table->string('dealer_name')->nullable();
            $table->string('dealer_mobile', 20)->nullable();
            $table->text('dealer_address')->nullable();
            $table->string('dealer_city')->nullable();
            $table->string('dealer_pincode', 20)->nullable();

            $table->enum('status', ['Pending', 'Approved', 'Dispatched', 'Delivered', 'Rejected'])->default('Pending');
            $table->text('admin_remark')->nullable();

            // Admin -> dealer leg
            $table->string('courier_name')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamp('dispatched_at')->nullable();

            // Dealer -> carpenter leg
            $table->timestamp('delivered_at')->nullable();
            $table->string('delivered_remark')->nullable();

            $table->enum('added_from', ['app', 'admin'])->default('app');
            $table->string('ip')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('dealer_id');
            $table->index('status');
            $table->index('reward_product_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reward_claims');
    }
}
