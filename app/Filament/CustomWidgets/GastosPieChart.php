<?php

namespace App\Filament\CustomWidgets;

use Filament\Widgets\PieChartWidget;
use App\Models\Order;
use App\Models\OtherExpense;
use Filament\Widgets\ChartWidget;

class GastosPieChart extends ChartWidget
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
        return 'Productos con mas Gastos (€' . number_format($this->total, 2) . ')';
    }


    protected function getData(): array
    {
        $gastos = OtherExpense::all();

        $data = [];
        foreach ($gastos as $gasto) {
            foreach ($gasto->details as $detail) {

                $nombre = $detail->item?->name ?? 'Sin nombre';

                if (!isset($data[$nombre])) {
                    $data[$nombre] = 0;
                }

                $data[$nombre] = round($data[$nombre] + $detail->price, 2);
                $this->total +=  $detail->price;
            }
        }

        // Aquí etiquetas con el monto y €
        $labels = [];
        foreach ($data as $nombre => $monto) {
            $labels[] = "{$nombre} (€{$monto})";
        }

        $backgroundColors = [];
        foreach ($data as $nombre => $_) {
            $backgroundColors[] = \App\Services\UtilsService::generateColorByName($nombre);
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
}
