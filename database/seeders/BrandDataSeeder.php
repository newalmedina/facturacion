<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandDataSeeder extends Seeder
{
    /**
     * Ejecuta las semillas de la base de datos.
     *
     * @return void
     */
    public function run()
    {
        // Array con las marcas de boxeo y sus descripciones
        $brands = [
            [
                'name' => 'Everlast',
                'description' => 'Everlast es una marca icónica conocida por sus guantes y equipo de boxeo de alta calidad.',
            ],
            [
                'name' => 'Title Boxing',
                'description' => 'Title Boxing es famosa por sus guantes de boxeo, sacos de entrenamiento y accesorios para boxeo.',
            ],
            [
                'name' => 'Adidas Boxing',
                'description' => 'Adidas ofrece equipo de boxeo profesional, incluyendo guantes y ropa deportiva de alto rendimiento.',
            ],
            [
                'name' => 'Hayabusa',
                'description' => 'Hayabusa es una marca conocida por sus guantes y equipo de artes marciales mixtas, especialmente en boxeo.',
            ],
            [
                'name' => 'Ringside',
                'description' => 'Ringside es una marca de boxeo que ofrece guantes, sacos de boxeo y accesorios de calidad para entrenamientos intensos.',
            ],
            [
                'name' => 'Venum',
                'description' => 'Venum es una marca globalmente reconocida por sus guantes de boxeo y equipo de artes marciales, combinando estilo y rendimiento.',
            ],
        ];

        // Recorrer el array y crear las marcas
        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand['name'],
                'description' => $brand['description'],
            ]);
        }
    }
}
