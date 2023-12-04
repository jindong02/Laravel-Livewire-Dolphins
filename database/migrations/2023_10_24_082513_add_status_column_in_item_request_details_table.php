<?php

use App\Enums\ItemRequestDetailStatus;
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
        Schema::table('item_request_details', function (Blueprint $table) {
            $table->string('status')->nullable()->default(ItemRequestDetailStatus::FOR_APPROVAL);
            $table->string('rejection_remarks')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_request_details', function (Blueprint $table) {
            //
        });
    }
};
