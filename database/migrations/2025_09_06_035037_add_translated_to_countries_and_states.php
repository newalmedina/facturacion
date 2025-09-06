<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar columna a countries
        Schema::table('countries', function (Blueprint $table) {
            $table->boolean('translated')->default(false)->after('name');
        });

        // Agregar columna a states
        Schema::table('states', function (Blueprint $table) {
            $table->boolean('translated')->default(false)->after('name');
        });
    }

    public function down(): void
    {
        // Eliminar columna en caso de rollback
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('translated');
        });

        Schema::table('states', function (Blueprint $table) {
            $table->dropColumn('translated');
        });
    }
};
