<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUniqueIndexFromPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Elimina el índice único de la columna 'document' en la tabla 'persons'
        Schema::table('persons', function (Blueprint $table) {
            $table->dropUnique(['document']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Vuelve a agregar el índice único a la columna 'document' en la tabla 'persons'
        Schema::table('persons', function (Blueprint $table) {
        });
    }
}

