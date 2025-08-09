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
        Schema::create('appointment_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // $table->foreignId('worker_id')->constrained('users')->onDelete('cascade')->nullable(); // worker
            $table->unsignedBigInteger('worker_id')->nullable(); // primero defines la columna
            $table->foreign('worker_id')->references('id')->on('users')->onDelete('cascade'); // luego defines la relación

            $table->boolean('active')->default(true);
            $table->boolean('is_general')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_templates');
    }
};
