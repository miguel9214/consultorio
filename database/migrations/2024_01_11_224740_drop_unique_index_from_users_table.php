<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUniqueIndexFromUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Elimina el índice único de la columna 'email' en la tabla 'users'
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Vuelve a agregar el índice único a la columna 'email' en la tabla 'users'
        Schema::table('users', function (Blueprint $table) {
            $table->unique('email');
        });
    }
}

