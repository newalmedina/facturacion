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
        ];

        foreach ($items as $item) {
            OtherExpenseItem::firstOrCreate($item);
        }
    }
}
