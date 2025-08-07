<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateStatusEnumInAppointmentsTable extends Migration
{
    public function up(): void
    {
        // Cambiar el enum del campo status
        DB::statement("ALTER TABLE appointments MODIFY status ENUM('confirmed', 'cancelled', 'expired', 'available') DEFAULT 'available'");
    }

    public function down(): void
    {
        // Revertir al enum anterior
        DB::statement("ALTER TABLE appointments MODIFY status ENUM('pending', 'confirmed', 'accepted', 'cancelled') DEFAULT NULL");
    }
}
