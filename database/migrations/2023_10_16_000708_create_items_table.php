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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique()->comment('Item Code / Stock Keeping Unit Code');
            $table->string('name')->index();
            $table->text('description')->nullable()->default(null);
            $table->string('unit_of_measure');
            $table->float('unit_cost', 11, 2)->nullable()->default(0);
            $table->string('ipsas_code')->nullable()->default(null);
            $table->boolean('is_active')->nullable()->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
