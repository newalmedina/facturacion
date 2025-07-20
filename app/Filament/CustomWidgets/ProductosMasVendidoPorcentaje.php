<?php

namespace App\Filament\CustomWidgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class ProductosMasVendidoPorcentaje extends ChartWidget
{
    protected static ?string $heading = 'Top 5 productos más vendidos (%)';

    protected int|string|array $columnSpan = 1;
    protected static ?string $maxHeight = '400px';
    protected function getType(): string
    {
        return 'pie';
    }
    protected function getData(): array
    {
        $ventasPorVendedor = Order::sales()
            ->invoiced()
            ->with('assignedUser')
            ->get();

        $data = [];

        foreach ($ventasPorVendedor as $venta) {
            foreach ($venta->orderDetails as $detail) {
                $nombre = $detail->product_name_formatted ?? 'Sin nombre';

                if (!isset($data[$nombre])) {
                    $data[$nombre] = 0;
                }

                $data[$nombre] += $detail->quantity;
            }
        }

        // Ordenar por cantidad descendente y tomar solo los 5 más vendidos
        arsort($data);
        $data = array_slice($data, 0, 5, true);

        $total = array_sum($data);

        // Labels con nombre + porcentaje
        $labels = [];
        $porcentajes = [];

        foreach ($data as $nombre => $cantidad) {
            $porcentaje = $total > 0 ? round(($cantidad / $total) * 100, 2) : 0;
            $labels[] = "{$nombre} ({$porcentaje}%)";
            $porcentajes[] = $porcentaje;
        }

        $backgroundColors = [];
        foreach ($data as $nombre => $_) {
            $backgroundColors[] = $this->nameToColor($nombre);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $porcentajes,
                    'backgroundColor' => $backgroundColors,
                ],
            ],
        ];
    }

    private function nameToColor(string $name): string
    {
        $hash = md5($name);
        return '#' . substr($hash, 0, 6);
    }
}
