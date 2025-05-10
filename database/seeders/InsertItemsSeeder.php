<?php

namespace Database\Seeders;

use App\Models\Calendar;
use App\Models\Departament;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InsertItemsSeeder extends Seeder
{
    /**
     * foRun the database seeds.
     */
    public function run(): void
    {

        $productNames = [
            'Guantes de boxeo',
            'Saco de boxeo',
            'Manoplas de entrenamiento',
            'Vendas elásticas',
            'Protector bucal',
            'Casco de protección',
            'Cuerda para saltar',
            'Pera loca',
            'Saco de pared',
            'Tobilleras de compresión'
        ];

        $serviceNames = [
            'Revisión de equipo',
            'Mano de obra técnica',
            'Instalación de sacos',
            'Mantenimiento de ring',
            'Clases personalizadas',
            'Asesoría en compras',
            'Entrenamiento funcional',
            'Evaluación técnica'
        ];

        $items = [];

        for ($i = 0; $i < 20; $i++) {
            $isProduct = rand(0, 1) === 1;

            if ($isProduct && count($productNames)) {
                $name = array_shift($productNames);
                $items[] = [
                    'type' => 'product',
                    'name' => $name,
                    'description' => 'Producto: ' . $name,
                    'active' => true,
                    'brand_id' => rand(1, 5),
                    'supplier_id' => rand(1, 5),
                    'price' => rand(3000, 15000) / 100,
                    'amount' => rand(1, 100),
                    'taxes' => rand(5, 21),
                    'category_id' => rand(1, 5),
                    'unit_of_measure_id' => rand(1, 5),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            } else {
                $name = $serviceNames[array_rand($serviceNames)];
                $items[] = [
                    'type' => 'service',
                    'name' => $name,
                    'description' => 'Servicio: ' . $name,
                    'active' => true,
                    'brand_id' => null,
                    'supplier_id' => null,
                    'price' => rand(1000, 5000) / 100,
                    'amount' => null,
                    'taxes' => rand(5, 21),
                    'category_id' => rand(1, 5),
                    'unit_of_measure_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('items')->insert($items);
    }
}
