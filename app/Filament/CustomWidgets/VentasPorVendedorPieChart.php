<?php

namespace App\Filament\CustomWidgets;

use Filament\Widgets\PieChartWidget;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class VentasPorVendedorPieChart extends ChartWidget
{


    // Aquí defines que el widget ocupe 1 columna (más pequeño)
    protected int|string|array $columnSpan = 1;
    protected static ?string $maxHeight = '400px';
    private $total = 0;
    protected function getType(): string
    {
        return 'doughnut';
    }

    public function getHeading(): ?string
    {
        return 'Ventas por Vendedor (€' . number_format($this->total, 2) . ')';
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
            $this->total +=  $venta->total;
        }

        // Aquí etiquetas con el monto y €
        $labels = [];
        foreach ($data as $nombre => $monto) {
            $labels[] = "{$nombre} (€{$monto})";
        }

        $backgroundColors = [];
        foreach ($data as $nombre => $_) {
            $backgroundColors[] = $this->nameToColor($nombre);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => array_values($data),
                    'backgroundColor' => $backgroundColors,
                ],
            ],
        ];
    }
    /*protected function getOptions(): array
    {
        $totalFormatted = '€' . number_format($this->total, 2);

        return [
            'plugins' => [
                'customCenterText' => [
                    'text' => $totalFormatted,
                ],
            ],
        ];
    }*/

    protected function getOptions(): array
    {
        $totalFormatted = '€' . number_format($this->total, 2);

        return [
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],

            ],
            'scales' => [
                'x' => [
                    'display' => false,
                    'grid' => ['display' => false],
                ],
                'y' => [
                    'display' => false,
                    'grid' => ['display' => false],
                ],
            ],
        ];
    }


    private function nameToColor(string $name): string
    {
        // Genera un color HEX a partir del hash MD5 del nombre
        $hash = md5($name);
        return '#' . substr($hash, 0, 6);
    }
}
