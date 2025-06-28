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
        Schema::create('other_expense_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('other_expense_id')->constrained('other_expenses')->onDelete('cascade');
            $table->foreignId('other_expense_item_id')->constrained('other_expense_items')->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('other_expense_details');
    }
};
