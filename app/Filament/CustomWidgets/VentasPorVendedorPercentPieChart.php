<?php

namespace App\Filament\CustomWidgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class VentasPorVendedorPercentPieChart extends ChartWidget
{
    protected static ?string $heading = 'Ventas por Vendedor (%)';

    // Tamaño más pequeño, 1 columna
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
            $nombre = $venta->assignedUser?->name ?? 'Sin vendedor';

            if (!isset($data[$nombre])) {
                $data[$nombre] = 0;
            }

            $data[$nombre] = round($data[$nombre] + $venta->total, 2);
        }

        $totalVentas = array_sum($data);

        // Labels con nombre y porcentaje
        $labels = [];
        foreach ($data as $nombre => $monto) {
            $porcentaje = $totalVentas > 0 ? round(($monto / $totalVentas) * 100, 2) : 0;
            $labels[] = "{$nombre} ({$porcentaje}%)";
        }

        $backgroundColors = [];
        foreach ($data as $nombre => $_) {
            $backgroundColors[] = $this->nameToColor($nombre);
        }

        // Datos en porcentaje para que el gráfico represente proporciones
        $dataPercent = [];
        foreach ($data as $monto) {
            $dataPercent[] = $totalVentas > 0 ? round(($monto / $totalVentas) * 100, 2) : 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $dataPercent,
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
