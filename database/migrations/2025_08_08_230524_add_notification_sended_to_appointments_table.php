<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Livewire\after;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->boolean('notification_sended')->after("active")->default(false);
            $table->string('requester_name')->after("status")->nullable();
            $table->integer('duration_minutes')->after("end_time")->nullable();
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('notification_sended');
            $table->dropColumn('requester_name');
            $table->dropColumn('duration_minutes');
        });
    }
};
