<?php

// database/migrations/2025_08_13_000002_create_cms_content_images_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cms_content_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_content_id')->constrained('cms_contents')->onDelete('cascade');
            $table->string('image_path')->nullable(); // ruta de la imagen
            $table->string('title')->nullable();      // título descriptivo
            $table->string('alt_text')->nullable();   // texto alternativo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_content_images');
    }
};
