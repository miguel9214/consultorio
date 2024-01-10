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
        Schema::create('persons', function (Blueprint $table) {
            $table->unsignedInteger("id")->autoIncrement();
            $table->enum('type_document', ['rc', 'ti', 'cc', 'ex'])->nullable();
            $table->string('document')->nullable()->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('sex', ['masculino', 'femenino'])->nullable();
            $table->string('phone');
            $table->date('birthdate');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('neighborhood');
            $table->unsignedSmallInteger("created_by_user")->nullable();
            $table->unsignedSmallInteger("updated_by_user")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};
