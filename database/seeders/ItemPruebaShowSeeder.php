<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Item;

class ItemPruebaShowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::chunk(100, function ($appointments) {
            foreach ($appointments as $appointment) {
                // Posibles combinaciones válidas:
                $options = [
                    ['show_booking' => true,  'show_booking_others' => false],
                    ['show_booking' => false, 'show_booking_others' => true],
                    ['show_booking' => false, 'show_booking_others' => false],
                ];

                // Seleccionar aleatoriamente una combinación válida
                $choice = $options[array_rand($options)];

                $appointment->update($choice);
            }
        });
    }
}
