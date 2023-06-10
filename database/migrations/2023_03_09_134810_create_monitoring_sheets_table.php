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
        Schema::create('monitoring_sheets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('record_id');
            $table->unsignedBigInteger('filled_by_id')->nullable();
            $table->foreign('record_id')->references('id')->on('medical_records')->cascadeOnDelete();
            $table->foreign('filled_by_id')->references('id')->on('users')->cascadeOnDelete();
            $table->date('filling_date');
            $table->integer('urine')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->double('weight')->nullable();
            $table->string('temperature')->nullable();
            $table->string('progress_report')->nullable();

            $table->timestamps();

//            $table->unsignedBigInteger('updated_by_id')->nullable();
//            $table->foreign('updated_by_id')->references('id')->on('staffs')->cascadeOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_sheets');
    }
};
