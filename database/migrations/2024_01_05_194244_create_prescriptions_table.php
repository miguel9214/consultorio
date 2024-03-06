<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->unsignedInteger("id")->autoIncrement();
            $table->date("date_prescription");
            $table->string('dose');
            $table->string('treatment');
            $table->string('additional_instructions');
            $table->unsignedInteger("consultation_id");
            $table->unsignedInteger("medicine_id");
            $table->timestamps();
            $table->foreign('consultation_id')->references('id')->on('consultations');
            $table->foreign('medicine_id')->references('id')->on('medicines');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
