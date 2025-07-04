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
                'name' => 'Corte de Cabello',
                'description' => 'Servicios profesionales de corte y estilizado para todo tipo de cabello.',
            ],
            [
                'name' => 'Peinados y Estilizados',
                'description' => 'Peinados para ocasiones especiales, eventos y el día a día.',
            ],
            [
                'name' => 'Manicura',
                'description' => 'Cuidado y embellecimiento de las uñas con diversos estilos y tratamientos.',
            ],
            [
                'name' => 'Pedicura',
                'description' => 'Tratamientos especializados para el cuidado y estética de los pies.',
            ],
            [
                'name' => 'Trenzas y Peinados Étnicos',
                'description' => 'Trenzas, cornrows y estilos tradicionales y modernos para todo tipo de cabello.',
            ],
            [
                'name' => 'Uñas Acrílicas y Gel',
                'description' => 'Aplicación de uñas acrílicas, gel y diseños personalizados para uñas largas y resistentes.',
            ],
            [
                'name' => 'Decoración de Uñas',
                'description' => 'Diseños artísticos, nail art, esmaltes especiales y accesorios para uñas.',
            ],
            [
                'name' => 'Extensiones de Cabello',
                'description' => 'Colocación de extensiones para dar volumen y longitud al cabello.',
            ],
            [
                'name' => 'Alisados y Rizos',
                'description' => 'Tratamientos para alisar o definir rizos de forma profesional y duradera.',
            ],
            [
                'name' => 'Tintura y Coloración',
                'description' => 'Cambio de color, mechas y reflejos para personalizar tu look.',
            ],
            [
                'name' => 'Otros',
                'description' => 'Servicios adicionales como asesorías, cuidado facial básico y otras atenciones personalizadas.',
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
