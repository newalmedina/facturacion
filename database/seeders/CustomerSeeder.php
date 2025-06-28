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

        for ($i = 0; $i < 30; $i++) {
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
        }
    }
}
