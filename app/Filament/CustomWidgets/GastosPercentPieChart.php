<?php

namespace App\Filament\CustomWidgets;

use Filament\Widgets\ChartWidget;
use App\Models\OtherExpense;

class GastosPercentPieChart extends ChartWidget
{
    protected int|string|array $columnSpan = 1;
    protected static ?string $maxHeight = '400px';
    private float $total = 0;

    protected function getType(): string
    {
        return 'pie';
    }

    public function getHeading(): ?string
    {
        return 'Gastos por Producto (%)';
    }

    protected function getData(): array
    {
        $gastos = OtherExpense::with('details.item')->get();

        $data = [];

        foreach ($gastos as $gasto) {
            foreach ($gasto->details as $detail) {
                $nombre = $detail->item?->name ?? 'Sin nombre';

                if (!isset($data[$nombre])) {
                    $data[$nombre] = 0;
                }

                $data[$nombre] += $detail->price;
                $this->total += $detail->price;
            }
        }

        if ($this->total == 0) {
            return [
                'labels' => [],
                'datasets' => [[]],
            ];
        }

        $labels = [];
        $percentages = [];
        foreach ($data as $nombre => $monto) {
            $porcentaje = round(($monto / $this->total) * 100, 1);
            $labels[] = "{$nombre} ({$porcentaje}%)";
            $percentages[] = $porcentaje;
        }

        $backgroundColors = [];
        foreach ($data as $nombre => $_) {
            $backgroundColors[] = $this->nameToColor($nombre);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $percentages,
                    'backgroundColor' => $backgroundColors,
                ],
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'x' => [
                    'display' => false,
                ],
                'y' => [
                    'display' => false,
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
