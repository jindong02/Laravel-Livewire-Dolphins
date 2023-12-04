<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plot_modules', function (Blueprint $table) {
            $table->id();
            $table->date('dates')->nullable()->default('2020-01-01');
            $table->integer('qty_sold')->default(0);
            $table->integer('qty_remain')->default(0);
            $table->integer('item_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plot_modules');
    }
};