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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('type', ['sale', 'purchase', 'quote']);
            $table->foreignId('customer_id')
                ->constrained()
                ->onDelete('restrict'); // impide borrar un cliente con órdenes

            $table->enum('status', ['pending', 'invoiced']);
            $table->timestamps();
            $table->softDeletes(); // 👈 Aquí el soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
