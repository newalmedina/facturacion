<?php

namespace Database\Seeders;

use App\Models\Category; // Asegúrate de importar tu modelo Category
use Illuminate\Database\Seeder;

class CategoryDataSeeder extends Seeder
{
    /**
     * Ejecuta las semillas de la base de datos.
     *
     * @return void
     */
    public function run()
    {
        // Array con las categorías
        $categories = [
            [
                'name' => 'Equipos de Boxeo',
                'description' => 'Categoría que agrupa todos los equipos necesarios para practicar boxeo.',
            ],
            [
                'name' => 'Guantes de Boxeo',
                'description' => 'Categoría dedicada a los guantes para la práctica de boxeo, en diferentes tamaños y estilos.',
            ],
            [
                'name' => 'Ropa Deportiva',
                'description' => 'Categoría que incluye toda la ropa diseñada para la práctica de deportes, como camisetas, pantalones, etc.',
            ],
            [
                'name' => 'Accesorios de Entrenamiento',
                'description' => 'Categoría de accesorios adicionales como sacos de boxeo, manoplas y otros implementos de entrenamiento.',
            ],
            [
                'name' => 'Artes Marciales Mixtas (MMA)',
                'description' => 'Categoría destinada a los equipos y accesorios relacionados con las artes marciales mixtas.',
            ],
        ];

        // Recorrer el array y crear las categorías
        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'description' => $category['description'],
            ]);
        }
    }
}
