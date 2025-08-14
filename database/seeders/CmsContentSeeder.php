<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CmsContent;

class CmsContentSeeder extends Seeder
{
    public function run(): void
    {
        $contents = [
            [
                'title' => null, // nombre del negocio para el jumbotron
                'subtitle' => null,
                'component_description' => null,
                'body' => null,
                'secondary_text' => null,
                'active' => true,
                'slug' => 'header-jumbotron', // ya está en inglés
            ],
            [
                'title' => null, // nombre del negocio para el jumbotron
                'subtitle' => null,
                'component_description' => null,
                'body' => null,
                'secondary_text' => null,
                'active' => true,
                'slug' => 'about-us', // cambiado a inglés
            ],
            [
                'title' => null, // nombre del negocio para el jumbotron
                'subtitle' => null,
                'component_description' => null,
                'body' => null,
                'secondary_text' => null,
                'active' => true,
                'slug' => 'discounts', // cambiado a inglés
            ],
            [
                'title' => null, // nombre del negocio para el jumbotron
                'subtitle' => null,
                'component_description' => null,
                'body' => null,
                'secondary_text' => null,
                'active' => true,
                'slug' => 'services', // cambiado a inglés
            ],
            [
                'title' => null, // nombre del negocio para el jumbotron
                'subtitle' => null,
                'component_description' => null,
                'body' => null,
                'secondary_text' => null,
                'active' => true,
                'slug' => 'price-catalog', // cambiado a inglés
            ],
        ];

        foreach ($contents as $content) {
            CmsContent::create($content);
        }
    }
}
