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
                ->onDelete('cascade'); // esta no es nullable por lógica

            $table->string("product_name", 100)->nullable();

            $table->foreignId('item_id')
                ->nullable()          // <-- aquí primero nullable
                ->constrained()
                ->onDelete('restrict');

            $table->decimal('original_price', 10, 2)->nullable();
            $table->decimal('price', 10, 2)->default();
            $table->decimal('taxes', 10, 2)->default();
            $table->integer('quantity')->nullable();
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
