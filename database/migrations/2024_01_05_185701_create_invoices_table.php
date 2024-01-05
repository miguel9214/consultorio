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
        Schema::create('invoices', function (Blueprint $table) {
            $table->unsignedInteger("id")->autoIncrement();
            $table->string("invoice_number");
            $table->date("due_date");
            $table->decimal('total_amount');
            $table->string("taxes");
            $table->string("discounts");
            $table->bigInteger('amount_paid')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
