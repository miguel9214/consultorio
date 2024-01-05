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
        Schema::create('pacient', function (Blueprint $table) {
            $table->unsignedInteger("id")->autoIncrement();
            $table->string("affilliate_type");
            $table->unsignedInteger("person_id");
            $table->unsignedSmallInteger("eps_id");
            $table->timestamps();
            $table->foreign('person_id')->references('id')->on('persons');
            $table->foreign('eps_id')->references('id')->on('eps');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacient');
    }
};
