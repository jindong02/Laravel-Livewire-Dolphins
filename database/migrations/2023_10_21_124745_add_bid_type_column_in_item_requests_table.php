<?php

use App\Enums\BidType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('item_requests', function (Blueprint $table) {
            $table->string('bid_type')->nullable()->default(BidType::LOT);
            $table->boolean('is_allowed_to_update')->nullable()->default(false);
            $table->string('rejection_remarks')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_requests', function (Blueprint $table) {
            $table->dropColumn('bid_type');
            $table->dropColumn('is_allowed_to_update');
            $table->dropColumn('rejection_remarks');
        });
    }
};
