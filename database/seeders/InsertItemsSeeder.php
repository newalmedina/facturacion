<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class InsertItemsSeeder extends Seeder
{
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

        foreach ($productNames as $name) {
            DB::table('items')->updateOrInsert(
                ['name' => $name, 'type' => 'product'],
                [
                    'description' => 'Producto: ' . $name,
                    'active' => true,
                    'brand_id' => rand(1, 5),
                    'supplier_id' => rand(1, 5),
                    'price' => rand(3000, 15000) / 100,
                    'amount' => rand(1, 100),
                    'taxes' => rand(5, 21),
                    'category_id' => rand(1, 5),
                    'unit_of_measure_id' => rand(1, 5),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        foreach ($serviceNames as $name) {
            DB::table('items')->updateOrInsert(
                ['name' => $name, 'type' => 'service'],
                [
                    'description' => 'Servicio: ' . $name,
                    'active' => true,
                    'brand_id' => null,
                    'supplier_id' => null,
                    'price' => rand(1000, 5000) / 100,
                    'amount' => null,
                    'taxes' => rand(5, 21),
                    'category_id' => rand(1, 5),
                    'unit_of_measure_id' => null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
