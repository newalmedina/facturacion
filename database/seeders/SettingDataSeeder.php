<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keys = [
            'general.image',
            'general.brand_name',
            'general.email',
            'general.phone',
            'general.country_id',
            'general.state_id',
            'general.city_id',
            'general.postal_code',
            'general.address',
        ];
       

        // Recorremos el array de keys y realizamos un insert para cada uno
        // Recorremos el array de keys y realizamos un insert para cada uno utilizando save
        foreach ($keys as $key) {
            // Verificamos si ya existe el key, si no existe lo creamos
            Setting::firstOrCreate(
                ['key' => $key],  // Condición de búsqueda
                ['value' => json_encode([])]  // Insertamos un JSON vacío válido
            );
        }
    }
}
