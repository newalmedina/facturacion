<?php
// database/migrations/2025_08_13_000001_create_cms_contents_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cms_contents', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('component_description')->nullable();
            $table->text('body')->nullable();
            $table->text('secondary_text')->nullable();
            $table->string('tertiary_text')->nullable();
            $table->string('slug')->unique();
            $table->string('image_path')->nullable(); // ruta de la imagen
            $table->string('image_title')->nullable();      // título descriptivo
            $table->string('image_alt_text')->nullable();   // texto alternativo
            $table->boolean('active')->default(true);

            $table->text('facebook_url')->nullable();
            $table->text('twitter_url')->nullable();
            $table->text('instagram_url')->nullable();
            $table->text('youtube_url')->nullable();
            $table->text('whatsapp_url')->nullable();

            $table->string('first_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_contents');
    }
};
