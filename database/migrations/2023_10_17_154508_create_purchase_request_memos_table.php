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
        Schema::create('purchase_request_memos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_request_id');
            $table->string('status');
            $table->text('notes')->nullable()->default(null);
            $table->date('memo_date');
            $table->json('options')->nullable()->default(null);
            $table->foreignId('created_by');
            $table->timestamps();

            $table->foreign('purchase_request_id')->references('id')->on('purchase_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_request_memos');
    }
};
