<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        if (app()->environment('production')) {
            // Crear solo un usuario general en producción
            Customer::create([
                'name'           => 'Usuario General',
                'email'          => 'usuario.general@ejemplo.com',
                'phone'          => '123-456-7890',
                'birth_date'     => '1990-01-01',
                'gender'         => 'masc',
                'identification' => '12345678',
                'address'        => 'Calle Falsa 123',
                'postal_code'    => '00000',
                'image'          => 'https://via.placeholder.com/640x480.png?text=Usuario+General',
                'active'         => true,
            ]);
        } else {
            // En otros entornos, crear 30 usuarios con Faker
            $faker = \Faker\Factory::create();

            for ($i = 0; $i < 30; $i++) {
                Customer::create([
                    'name'           => $faker->name,
                    'email'          => $faker->unique()->safeEmail,
                    'phone'          => $faker->phoneNumber,
                    'birth_date'     => $faker->date(),
                    'gender'         => $faker->randomElement(['masc', 'fem']),
                    'identification' => $faker->optional()->numerify('########'),
                    'address'        => $faker->address,
                    'postal_code'    => $faker->postcode,
                    'image'          => $faker->imageUrl(640, 480, 'people', true),
                    'active'         => $faker->boolean,
                ]);
            }
        }
        /*for ($i = 0; $i < 30; $i++) {
            Customer::create([
                'name'            => $faker->name,
                'email'           => $faker->unique()->safeEmail,
                'phone'           => $faker->phoneNumber,
                'birth_date'      => $faker->date(),
                'gender'          => $faker->randomElement(['masc', 'fem']),
                'identification'  => $faker->optional()->numerify('########'),
                'address'         => $faker->address,
                'postal_code'     => $faker->postcode,
                'image'           => $faker->imageUrl(640, 480, 'people', true),
                'active'          => $faker->boolean,
            ]);
        }*/
    }
}
