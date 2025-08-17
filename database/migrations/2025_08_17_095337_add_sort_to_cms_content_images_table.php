<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_content_images', function (Blueprint $table) {
            $table->integer('sort')
                ->nullable()
                ->after('active'); // 👈 se agrega detrás del campo active
        });
    }

    public function down(): void
    {
        Schema::table('cms_content_images', function (Blueprint $table) {
            $table->dropColumn('sort');
        });
    }
};
