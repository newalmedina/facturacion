<?php

namespace Database\Seeders;

use App\Models\OtherExpenseItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OtherExpenseItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'Comida'],
            ['name' => 'Taxi'],
            ['name' => 'Parking'],
            ['name' => 'Taxi'],
            ['name' => 'Parking'],
            ['name' => 'Hospedaje'],
            ['name' => 'Material de oficina'],
            ['name' => 'Transporte'],
            ['name' => 'Combustible'],
            ['name' => 'Mantenimiento'],
            ['name' => 'Suministros'],
            ['name' => 'Papelería'],
            ['name' => 'Software'],
            ['name' => 'Licencias'],
            ['name' => 'Publicidad'],
            ['name' => 'Marketing digital'],
            ['name' => 'Servicios legales'],
            ['name' => 'Consultoría externa'],
            ['name' => 'Honorarios profesionales'],
            ['name' => 'Reparaciones'],
            ['name' => 'Limpieza'],
            ['name' => 'Teléfono corporativo'],
            ['name' => 'Internet'],
            ['name' => 'Hosting'],
            ['name' => 'Suscripciones'],
            ['name' => 'Servicios contables'],
            ['name' => 'Cursos y formación'],
            ['name' => 'Seguros'],
            ['name' => 'Equipos tecnológicos'],
            ['name' => 'Impresiones'],
            ['name' => 'Refrigerios'],
            ['name' => 'Eventos y reuniones'],
            ['name' => 'Alquiler de equipo'],
            ['name' => 'Gastos bancarios'],
            ['name' => 'Donaciones'],
            ['name' => 'Regalos corporativos'],
            ['name' => 'Uniformes'],
            ['name' => 'Comisiones']
        ];

        foreach ($items as $item) {
            OtherExpenseItem::firstOrCreate($item);
        }
    }
}
