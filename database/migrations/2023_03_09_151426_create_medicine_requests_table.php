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
        Schema::create('medicine_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unsignedBigInteger('record_id');
            $table->foreign('record_id')->references('id')->on('medical_records')->cascadeOnDelete();
            $table->integer('quantity');
            $table->string('status')->nullable();
            $table->string('review')->nullable();
            $table->timestamps();
        });

        Schema::create('medicine_request_medicine', function (Blueprint $table) {

            $table->unsignedBigInteger('medicine_request_id');
            $table->foreign('medicine_request_id')->references('id')->on('medicine_requests')->cascadeOnDelete();
            $table->unsignedBigInteger('medicine_id');
            $table->foreign('medicine_id')->references('id')->on('medicines')->cascadeOnDelete();

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_requests');
    }
};
