<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Administrador',
            'email' => 'ing.newal.medina@gmail.com',
            'password' => Hash::make("Secret15*"),
        ]);

        /*$this->call(InsertDataSeeder::class);
        $this->call(UnitOfMeasureSeeder::class);
        $this->call(BrandDataSeeder::class);
        $this->call(CategoryDataSeeder::class);
        $this->call(SupplierDataSeeder::class);
        $this->call(SettingDataSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(OtherExpenseItemSeeder::class);
        $this->call(InsertItemsSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(OtherExpenseSeeder::class);*/
        $this->call(WorldTableSeeder::class);
    }
}
