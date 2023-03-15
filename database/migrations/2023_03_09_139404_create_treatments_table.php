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
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('monitoring_sheet_id');
            $table->unsignedBigInteger('medicine_id');
            $table->foreign('monitoring_sheet_id')->references('id')->on('monitoring_sheets')->cascadeOnDelete();
//            $table->foreign('medicine_id')->references('id')->on('medicines')->cascadeOnDelete();
            $table->string('name');
            $table->string('dose');
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
