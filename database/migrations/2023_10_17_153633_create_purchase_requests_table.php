<?php

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
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_request_number')->comment('Formatted PR No. PR-YY-MM-DD-NNNNN');
            $table->string('fund')->nullable()->default(null);
            $table->string('code_pap')->nullable()->default(null);
            $table->string('program')->nullable()->default(null);
            $table->string('object_code')->nullable()->default(null);
            $table->string('bid_type')->nullable()->default(null);
            $table->string('status')->comment('Reference to purchase_request_statuses');
            $table->foreignId('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};
