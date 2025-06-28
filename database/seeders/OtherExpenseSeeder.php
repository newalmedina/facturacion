<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OtherExpense;
use App\Models\OtherExpenseDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OtherExpenseSeeder extends Seeder
{
    public function run(): void
    {
        // Paso 1: Agrupar órdenes por fecha y sumar monto total de venta (sin impuestos)
        $ordersByDate = Order::with(['orderDetails'])
            ->get()
            ->groupBy(fn($order) => \Carbon\Carbon::parse($order->date)->format('Y-m-d'))
            ->map(function ($orders) {
                return $orders->flatMap->orderDetails
                    ->sum(fn($d) => $d->price * $d->quantity);
            });

        // Paso 2: Insertar otros gastos en esas fechas
        $totalGastos = 20000;
        $batchSize = 500;
        $fechas = $ordersByDate->keys()->shuffle();

        $inserted = 0;

        foreach ($fechas as $fecha) {
            if ($inserted >= $totalGastos) break;

            $disponible = $ordersByDate[$fecha];
            $gastoAcumulado = 0;
            $otrosGastos = [];

            while ($gastoAcumulado < $disponible && $inserted < $totalGastos) {
                // Genera entre 1 y 3 detalles por gasto
                $detalleCount = rand(1, 3);
                $detalles = [];
                $subtotal = 0;

                for ($i = 0; $i < $detalleCount; $i++) {
                    $precio = rand(200, 5000) / 100;
                    if ($gastoAcumulado + $subtotal + $precio > $disponible) break;

                    $detalles[] = [
                        'other_expense_item_id' => rand(1, 30),
                        'price' => $precio,
                        'observations' => 'Detalle ' . Str::random(8),
                        'created_at' => $fecha,
                        'updated_at' => $fecha,
                    ];

                    $subtotal += $precio;
                }

                if (count($detalles) === 0) break;

                // Crea el OtherExpense y luego los detalles
                $other = OtherExpense::create([
                    'date' => $fecha,
                    'description' => 'Gasto automático ' . Str::random(6),
                    'created_at' => $fecha,
                    'updated_at' => $fecha,
                ]);

                foreach ($detalles as &$detalle) {
                    $detalle['other_expense_id'] = $other->id;
                }

                OtherExpenseDetail::insert($detalles);

                $gastoAcumulado += $subtotal;
                $inserted++;

                if ($inserted % $batchSize === 0) {
                    $this->command->info("Insertados $inserted otros gastos.");
                }
            }
        }

        $this->command->info("✔️ Finalizados $inserted registros en otros gastos.");
    }
}
