<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['product', 'service']); // To differentiate between product and service
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);

            // Product-specific fields
            $table->foreignId('brand_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('price', 10, 2)->nullable(); // Price for both product and service
            $table->decimal('amount', 10, 2)->nullable(); // For products
            $table->decimal('taxes', 10, 2)->nullable(); // Taxes for both product and service

            // Adding category and unit of measure
            $table->foreignId('category_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->unsignedBigInteger('unit_of_measure_id')->nullable(); // Use unsignedBigInteger

            // Foreign key constraint for unit_of_measure_id
            $table->foreign('unit_of_measure_id')
                ->references('id')
                ->on('unit_of_measures')
                ->onUpdate('cascade')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
}
