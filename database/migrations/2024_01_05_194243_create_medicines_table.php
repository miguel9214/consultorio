<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->unsignedInteger("id")->autoIncrement();
            $table->string("code");
            $table->string("name");
            $table->string("dosage");
            $table->string("dosing_frequency");
            $table->string("indications");
            $table->string("contraindications");
            $table->string("administration_method");
            $table->string("pharmaceutical_laboratory");
            $table->float('price');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
