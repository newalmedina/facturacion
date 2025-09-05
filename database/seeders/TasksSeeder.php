<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TasksSeeder extends Seeder
{
    public function run(): void
    {
        $hoy = Carbon::now();

        $statuses = ['pending', 'completed', 'cancelled'];

        $tasks = [
            [
                'name' => 'Reunión de equipo',
                'start' => $hoy->copy()->addHours(9),
                'end' => $hoy->copy()->addHours(10),
                'status' => $statuses[array_rand($statuses)],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Presentación de proyecto',
                'start' => $hoy->copy()->addDays(1)->setHour(14),
                'end' => $hoy->copy()->addDays(1)->setHour(15),
                'status' => $statuses[array_rand($statuses)],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Evaluación mensual',
                'start' => $hoy->copy()->addDays(3)->setHour(11),
                'end' => $hoy->copy()->addDays(3)->setHour(12),
                'status' => $statuses[array_rand($statuses)],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fiesta de oficina',
                'start' => $hoy->copy()->addDays(7)->setHour(18),
                'end' => $hoy->copy()->addDays(7)->setHour(20),
                'status' => $statuses[array_rand($statuses)],
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tasks')->insert($tasks);
    }
}
