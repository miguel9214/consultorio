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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->unsignedInteger("id")->autoIncrement();
            $table->string("dosage");
            $table->date("observacion");
            $table->decimal('diagnosis');
            $table->unsignedInteger("consultation_id");
            $table->timestamps();
            $table->foreign('consultation_id')->references('id')->on('consultations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
