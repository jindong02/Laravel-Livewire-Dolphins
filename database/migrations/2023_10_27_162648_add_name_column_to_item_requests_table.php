<?php

use App\Enums\BidType;
use App\Models\ItemRequest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('item_requests', function (Blueprint $table) {
            $table->string('name')->nullable()->default(null)->after('id');
        });

        ItemRequest::where('bid_type', BidType::LOT)
            ->update(['name' => DB::raw("CONCAT('LOT - #', id)")]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_requests', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
