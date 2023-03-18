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
        Schema::create('mandatory_declaration', function (Blueprint $table) {
            $table->id();
            $table->string('diagnosis');
            $table->string('detail');
            $table->unsignedBigInteger('medical_record_id')->unique();
            $table->foreign('medical_record_id')->references('id')->on('medical_records')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mandatory_declaration');
    }
};
