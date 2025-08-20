<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'general.image'       => "",
            'general.brand_name'  => 'Keep Boxing',
            'general.email'       => 'info@keepboxing.com',
            'general.phone'       => '674987708',
            'general.country_id'  => "",
            'general.state_id'    => "",
            'general.city_id'     => "",
            'general.postal_code' => '09922',
            'general.address'     => 'fonollar 6, planta baja',
            'general.allow_appointment'     => true,
        ];

        foreach ($settings as $key => $value) {
            Setting::firstOrCreate(
                ['key' => $key],
                ['value' => json_encode($value)]  // Asegura que todos los valores sean JSON válidos
            );
        }
    }
}
