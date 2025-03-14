<?php

namespace Database\Seeders;

use App\Models\Calendar;
use App\Models\Departament;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InsertDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $users = [
            ['name' => 'Newal Medina', 'email' => 'nmedina@correo.com'],
            ['name' => 'Anireily Gómez', 'email' => 'ngomez@correo.com'],
            ['name' => 'Juan Pérez', 'email' => 'juan.perez@correo.com'],
            ['name' => 'Ana Gómez', 'email' => 'ana.gomez@correo.com'],
            ['name' => 'Carlos López', 'email' => 'carlos.lopez@correo.com'],
            ['name' => 'Lucía Rodríguez', 'email' => 'lucia.rodriguez@correo.com'],
            ['name' => 'Pedro Sánchez', 'email' => 'pedro.sanchez@correo.com'],
            ['name' => 'María Fernández', 'email' => 'maria.fernandez@correo.com'],
            ['name' => 'José Martínez', 'email' => 'jose.martinez@correo.com'],
            ['name' => 'Laura García', 'email' => 'laura.garcia@correo.com'],
            ['name' => 'Luis Pérez', 'email' => 'luis.perez@correo.com'],
            ['name' => 'Elena Díaz', 'email' => 'elena.diaz@correo.com']
        ];
        foreach ($users as $userData) {
            // Verificar si el usuario ya existe
            if (!User::where('email', $userData['email'])->exists()) {
                User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('Secret15*'), // Crear el password con hash
                    'active' => rand(0, 1) // Valor aleatorio para el campo 'active' (0 o 1)
                ]);
            }
        }
        // Inserción de departamentos
        $departaments = ['Informatica', 'Recursos Humanos', 'Contabilidad', 'Marketing', 'Ventas'];

        foreach ($departaments as $departamentName) {
            // Verificar si el departamento ya existe
            if (!Departament::where('name', $departamentName)->exists()) {
                Departament::create(['name' => $departamentName]);
            }
        }

        // Inserción de calendarios
        $currentYear = Carbon::now()->year;

        for ($i = 0; $i < 3; $i++) {
            // El primer año será el actual, el segundo será el anterior, el tercero será otro año anterior
            $year = $currentYear - $i;

            // Verificar si el calendario ya existe
            if (!Calendar::where('year', $year)->where("name", "Calendario Laboral $year")->exists()) {
                Calendar::create([
                    'name' => "Calendario Laboral $year",
                    'year' => $year,
                    'active' => $i == 0 ? 1 : 0, // Solo el calendario del año actual estará activo
                ]);
            }
        }
    }
}
