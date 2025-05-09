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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->constrained()
                ->onDelete('cascade')->nullable(); // si se borra la orden, se borran los detalles
                
            $table->string("product_name", 100)->nullable();

            $table->foreignId('item_id')
                ->constrained()
                ->onDelete('restrict'); // impide borrar un item con ventas

            $table->decimal('original_price', 10, 2);
            $table->decimal('price', 10, 2);
            $table->decimal('taxes', 10, 2);
            $table->integer('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
