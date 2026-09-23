<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRedemptionCodeToRewardClaimsTable extends Migration
{
    public function up()
    {
        Schema::table('reward_claims', function (Blueprint $table) {
            // Generated the moment the admin approves the claim; the carpenter
            // sees it in the app and reads it out to the dealer for pickup.
            $table->string('redemption_code', 6)->nullable()->after('status');
            $table->timestamp('code_generated_at')->nullable()->after('redemption_code');

            // Stamped when the dealer's WhatsApp bot verifies the code - kept
            // separate from delivered_at so an admin manual deliver (no code
            // involved) is distinguishable from a bot-verified one.
            $table->timestamp('code_verified_at')->nullable()->after('delivered_remark');

            $table->index('redemption_code');
        });
    }

    public function down()
    {
        Schema::table('reward_claims', function (Blueprint $table) {
            $table->dropIndex(['redemption_code']);
            $table->dropColumn(['redemption_code', 'code_generated_at', 'code_verified_at']);
        });
    }
}
