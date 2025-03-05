<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierDataSeeder extends Seeder
{
    public function run()
    {
        // Crear 5 proveedores relacionados con el mundo del boxeo
        Supplier::create([
            'name' => 'Boxing Gear Pro',
            'description' => 'Proveedor de equipos de boxeo de alta calidad, incluyendo guantes, sacos y protecciones.',
            'contact_name' => 'Pedro González',
            'contact_identification' => 'B123456789',
            'contact_email' => 'pedro@boxinggearpro.com',
            'contact_phone' => '+34 912 345 678',
            'address' => 'Calle del Boxeo 25, Madrid',
            'postal_code' => '28020',
        ]);

        Supplier::create([
            'name' => 'Fight Ready Equipment',
            'description' => 'Venta de ropa, guantes, y equipo de protección para boxeadores profesionales y amateurs.',
            'contact_name' => 'Laura Martínez',
            'contact_identification' => 'A987654321',
            'contact_email' => 'laura@fightready.com',
            'contact_phone' => '+34 933 456 789',
            'address' => 'Avenida del Boxeo 100, Barcelona',
            'postal_code' => '08015',
        ]);

        Supplier::create([
            'name' => 'Boxer World',
            'description' => 'Distribuidor de entrenadores y equipos completos para gimnasios de boxeo.',
            'contact_name' => 'Carlos Rodríguez',
            'contact_identification' => 'C112233445',
            'contact_email' => 'carlos@boxerworld.com',
            'contact_phone' => '+34 934 567 890',
            'address' => 'Carrer de la Boxa 12, Valencia',
            'postal_code' => '46015',
        ]);

        Supplier::create([
            'name' => 'Punch Pro Gear',
            'description' => 'Proveedor de guantes de boxeo personalizados y accesorios exclusivos para boxeadores.',
            'contact_name' => 'Marta Sánchez',
            'contact_identification' => 'D556677889',
            'contact_email' => 'marta@punchprogear.com',
            'contact_phone' => '+34 952 678 901',
            'address' => 'Calle de la Pelea 45, Sevilla',
            'postal_code' => '41010',
        ]);

        Supplier::create([
            'name' => 'Boxing Academy Supplies',
            'description' => 'Proveedor especializado en materiales de entrenamiento para academias de boxeo.',
            'contact_name' => 'Luis Pérez',
            'contact_identification' => 'E998877665',
            'contact_email' => 'luis@boxingacademysupplies.com',
            'contact_phone' => '+34 912 678 901',
            'address' => 'Calle de los Combates 56, Madrid',
            'postal_code' => '28025',
        ]);
    }
}
