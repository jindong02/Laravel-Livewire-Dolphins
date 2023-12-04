<?php

use App\Enums\ItemRequestStatus;
use App\Models\ItemRequest;
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
        Schema::create('item_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mode_id');
            $table->foreignId('fund_source_id');
            $table->foreignId('supply_type_id');
            $table->string('method')->nullable()->default(null);
            $table->string('status')->nullable()->default(ItemRequestStatus::DRAFT);
            $table->foreignId('department_id');
            $table->foreignId('created_by');
            $table->timestamp('submitted_at')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_requests');
    }
};
