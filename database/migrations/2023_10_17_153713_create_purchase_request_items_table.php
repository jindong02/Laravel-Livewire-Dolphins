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
        Schema::create('purchase_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_request_id');
            $table->string('sku')->index();
            $table->string('description')->nullable()->default(null);
            $table->string('unit_of_measure');
            $table->float('quantity', 11, 2)->nullable()->default(0);
            $table->float('unit_cost', 11, 2)->nullable()->default(0);
            $table->float('total_cost', 11, 2)->nullable()->default(0);
            $table->timestamps();

            $table->foreign('purchase_request_id')->references('id')->on('purchase_requests')->onDelete('cascade');
            $table->unique(['purchase_request_id', 'sku']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_request_items');
    }
};
