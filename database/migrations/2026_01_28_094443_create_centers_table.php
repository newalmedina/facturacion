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
        Schema::create('centers', function (Blueprint $table) {
            $table->id(); // id bigIncrements unsigned
            $table->string('name', 255);
            $table->string('email', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('image', 255)->nullable();

            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();

            $table->string('postal_code', 255)->nullable();
            $table->string('address', 255)->nullable();

            $table->boolean('allow_appointment')->default(true);
            $table->boolean('has_home')->default(true);

            $table->string('bank_name', 255)->nullable();
            $table->string('bank_number', 255)->nullable();
            $table->string('nif', 255)->nullable();

            $table->boolean('mail_enable_integration')->default(false);

            $table->string('primary_color', 50)->default('#2D3748');
            $table->string('primary_color_soft', 50)->default('#b8b8b8');
            $table->string('secondary_color', 50)->nullable();

            $table->boolean('active')->default(true);

            $table->timestamps();


            // Relaciones
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centers');
    }
};
