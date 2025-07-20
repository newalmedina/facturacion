<?php

namespace App\Filament\CustomWidgets;

use Filament\Widgets\PieChartWidget;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class ProductosMasVendido extends ChartWidget
{
    //  protected static ?string $heading = 'Top 5 productos más vendidos (Cantidad) ';

    // Aquí defines que el widget ocupe 1 columna (más pequeño)
    protected int|string|array $columnSpan = 1;
    private $total = 0;
    protected static ?string $maxHeight = '400px';
    protected function getType(): string
    {
        return 'doughnut';
    }
    public function getHeading(): ?string
    {
        return 'Top 5 productos más vendidos  (' . number_format($this->total, 2) . ' Unidades)';
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
                $this->total +=  $detail->quantity;
            }
        }

        arsort($data);
        $data = array_slice($data, 0, 5, true);
        // Aquí etiquetas con el monto y €
        $labels = [];
        foreach ($data as $nombre => $monto) {
            $labels[] = "{$nombre} ({$monto})";
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
