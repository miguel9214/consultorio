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
        Schema::create('consultations', function (Blueprint $table) {
            $table->unsignedInteger("id")->autoIncrement();
            $table->string('observation')->nullable();
            $table->enum('status', ['completado', 'cancelado', 'pendiente', 'esperando']);
            $table->date('date')->nullable();
            $table->unsignedSmallInteger("created_by_user");
            $table->unsignedSmallInteger("updated_by_user");
            $table->unsignedSmallInteger("consultation_type_id");
            $table->unsignedInteger("doctor_id");
            $table->unsignedInteger("pacient_id");
            $table->timestamps();
            $table->foreign('consultation_type_id')->references('id')->on('consultation_types');
            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('pacient_id')->references('id')->on('patients');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
