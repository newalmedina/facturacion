<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // 🔹 Agregar campo customer_id para enlazar con la tabla customers
            $table->unsignedBigInteger('customer_id')->nullable()->after('id');

            // 🔹 Agregar campo booleano para saber si es un cliente del sistema
            $table->boolean('is_system_customer')->default(false)->after('customer_id');

            // 🔹 Crear relación foránea con customers.id
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('set null'); // o 'cascade' si quieres borrar citas al eliminar el cliente
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Eliminar la relación foránea primero
            $table->dropForeign(['customer_id']);

            // Eliminar las columnas agregadas
            $table->dropColumn(['customer_id', 'is_system_customer']);
        });
    }
};
