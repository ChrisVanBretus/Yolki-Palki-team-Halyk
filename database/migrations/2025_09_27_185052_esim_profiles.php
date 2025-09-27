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
        Schema::create('esim_profiles', function ($table) {
            $table->id();
            $table->unsignedBigInteger('subscriber_id');
            $table->string('operator');
            $table->string('status')->default('active');
            $table->string('activation_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esim_profiles');
    }
};
