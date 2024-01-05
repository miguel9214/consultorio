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
        Schema::create('medical_agendas', function (Blueprint $table) {
            $table->unsignedInteger("id")->autoIncrement();
            $table->date("start_date")->nullable();
            $table->date("end_date")->nullable();
            $table->unsignedInteger("doctor_id");
            $table->unsignedSmallInteger("consultation_type_id");
            $table->timestamps();
            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('consultation_type_id')->references('id')->on('consultation_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_agendas');
    }
};
