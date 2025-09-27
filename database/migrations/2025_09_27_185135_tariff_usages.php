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
        Schema::create('tariff_usages', function ($table) {
            $table->id();
            $table->unsignedBigInteger('subscriber_id');
            $table->string('tariff_name');
            $table->integer('data_used')->default(0);
            $table->integer('minutes_used')->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tariff_usages');
    }
};
