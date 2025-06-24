<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $totalOrders = 20000;
        $batchSize = 500; // Inserta en lotes

        for ($i = 0; $i < $totalOrders; $i += $batchSize) {
            $orders = [];

            for ($j = 0; $j < $batchSize; $j++) {
                $date = Carbon::createFromTimestamp(rand(
                    strtotime('2023-01-01'),
                    strtotime('now')
                ));

                $orders[] = [
                    'type' => 'sale',
                    'status' => rand(0, 1) ? 'pending' : 'invoiced',
                    'customer_id' => rand(1, 30),
                    'date' => $date->format('Y-m-d'),
                    'observations' => 'Observaciones ejemplo ' . Str::random(10),
                    'created_by' => 1, // Puedes cambiar esto si usas usuarios reales
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Inserta las órdenes sin código, el modelo lo generará automáticamente
            $insertedOrders = [];

            foreach ($orders as $orderData) {
                $order = Order::create($orderData);
                $insertedOrders[] = $order;
            }

            // Insertar detalles
            $details = [];

            foreach ($insertedOrders as $order) {
                $detailCount = rand(1, 5);

                for ($d = 0; $d < $detailCount; $d++) {
                    $itemId = rand(0, 100) > 15 ? rand(1, 18) : null; // 85% tiene item_id

                    $details[] = [
                        'order_id' => $order->id,
                        'item_id' => $itemId,
                        'product_name' => $itemId ? null : 'Producto libre ' . Str::random(5),
                        'original_price' => $original = rand(1000, 10000) / 100,
                        'price' => $price = $original - rand(0, 1000) / 100,
                        'taxes' => round($price * 0.18, 2),
                        'quantity' => rand(1, 10),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            OrderDetail::insert($details);

            $this->command->info("Insertados $batchSize órdenes con detalles");
        }
    }
}
