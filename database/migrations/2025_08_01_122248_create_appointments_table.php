<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('user_id')->constrained()->onDelete('cascade')->nullable(); // requester
            $table->foreignId('worker_id')->constrained('users')->onDelete('cascade')->nullable(); // worker
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');

            $table->enum('status', ['pending', 'confirmed', 'accepted', 'cancelled'])->nullable()->default(null);
            $table->string('requester_email')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('requester_phone')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
}
