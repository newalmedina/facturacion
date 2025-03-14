<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitOfMeasureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = [
            ['name' => 'Unidad', 'description' => 'Unidad genérica de medida para artículos.', 'active' => true],
            ['name' => 'Kilogramo', 'description' => 'Unidad de masa del sistema métrico.', 'active' => true],
            ['name' => 'Gramo', 'description' => 'Unidad métrica de masa, equivalente a una milésima parte de un kilogramo.', 'active' => true],
            ['name' => 'Libra', 'description' => 'Unidad de peso del sistema imperial.', 'active' => true],
            ['name' => 'Onza', 'description' => 'Unidad de peso equivalente a 1/16 de una libra.', 'active' => true],
            ['name' => 'Litro', 'description' => 'Unidad métrica de volumen.', 'active' => true],
            ['name' => 'Mililitro', 'description' => 'Unidad métrica de volumen, equivalente a una milésima parte de un litro.', 'active' => true],
            ['name' => 'Metro', 'description' => 'Unidad base de longitud del sistema métrico.', 'active' => true],
            ['name' => 'Centímetro', 'description' => 'Unidad de longitud equivalente a una centésima parte de un metro.', 'active' => true],
            ['name' => 'Pulgada', 'description' => 'Unidad de longitud en el sistema imperial, equivalente a 1/12 de un pie.', 'active' => true],
        ];

        DB::table('unit_of_measures')->insert($units);
    }
}
