<?php

namespace App\Filament\CustomWidgets;

use Filament\Widgets\PieChartWidget;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class ProductosMenosVendidoPorcentaje extends ChartWidget
{
    protected static ?string $heading = 'Top 5 productos menos vendidos (%)';

    protected int|string|array $columnSpan = 1;


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

        // Ordenar de menor a mayor y tomar los 5 menos vendidos
        asort($data);
        $data = array_slice($data, 0, 5, true);

        // Calcular total de estos 5 para porcentaje
        $total = array_sum($data);

        $labels = [];
        $percentData = [];

        foreach ($data as $nombre => $cantidad) {
            $porcentaje = $total > 0 ? round(($cantidad / $total) * 100, 2) : 0;
            $labels[] = "{$nombre} ({$porcentaje}%)";
            $percentData[] = $porcentaje;
        }

        $backgroundColors = [];
        foreach (array_keys($data) as $nombre) {
            $backgroundColors[] = $this->nameToColor($nombre);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $percentData,
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
