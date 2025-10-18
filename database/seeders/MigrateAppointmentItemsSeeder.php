<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MigrateAppointmentItemsSeeder extends Seeder
{
    public function run(): void
    {
        // Contamos cuántos appointments tienen item_id
        $appointments = DB::table('appointments')
            ->whereNotNull('item_id')
            ->get();

        $count = 0;

        foreach ($appointments as $appointment) {
            // Evitamos duplicar si ya existe un registro
            $exists = DB::table('appointment_items')
                ->where('appointment_id', $appointment->id)
                ->where('item_id', $appointment->item_id)
                ->exists();

            if (! $exists) {
                DB::table('appointment_items')->insert([
                    'appointment_id' => $appointment->id,
                    'item_id'        => $appointment->item_id,
                    'quantity'       => 1,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
                $count++;
            }
        }

        $this->command->info("✅ Migración completada: {$count} registros insertados en appointment_items.");
    }
}
