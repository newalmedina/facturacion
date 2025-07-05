<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignedUserToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Agregamos la columna 'assigned_user_id' que puede ser null
            $table->unsignedBigInteger('assigned_user_id')->nullable()->after('id');

            // Definimos la clave foránea que referencia a 'id' en tabla 'users'
            $table->foreign('assigned_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Primero quitamos la foreign key
            $table->dropForeign(['assigned_user_id']);

            // Luego eliminamos la columna
            $table->dropColumn('assigned_user_id');
        });
    }
}
