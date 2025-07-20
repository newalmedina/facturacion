<?php

namespace App\Filament\CustomWidgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class VentasMensualesChart extends ChartWidget
{


    protected static ?string $heading = 'Ventas Mensuales por Año';
    protected static ?string $maxHeight = '400px';
    protected function getType(): string
    {
        return 'line';
    }
    protected function getData(): array
    {
        $monthlySales = $this->getMonthlySalesByYear();

        return [
            'datasets' => $monthlySales,
            'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        ];
    }

    private function getMonthlySalesByYear(): array
    {
        $orders = Order::sales()
            ->invoiced()
            ->whereYear('date', '>=', now()->year - 2)
            ->get()
            ->groupBy(fn($order) => Carbon::parse($order->date)->year)
            ->map(function ($ordersByYear) {
                return $ordersByYear->groupBy(fn($order) => Carbon::parse($order->date)->format('m'))
                    ->map(fn($ordersByMonth) => $ordersByMonth->sum('total'));
            });

        $chartData = [];

        $months = collect(range(1, 12))->map(fn($m) => str_pad($m, 2, '0', STR_PAD_LEFT));

        $colors = [
            '#FF6384',
            '#36A2EB',
            '#FFCE56',
            '#4BC0C0',
            '#9966FF',
            '#FF9F40',
        ];

        $index = 0;
        foreach ($orders as $year => $monthlySales) {
            $chartData[] = [
                'label' => (string) $year,               // <- clave label, NO name
                'data' => $months->map(fn($month) => round($monthlySales->get($month, 0), 2))->toArray(),
                'borderColor' => $colors[$index % count($colors)],

                'backgroundColor' => 'transparent',
                'fill' => false,
                'pointRadius' => 3,
                'pointHoverRadius' => 8,
            ];
            $index++;
        }

        return $chartData;
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',  // o 'bottom'
                    'labels' => [
                        'usePointStyle' => false,  // <--- para que sea cuadrado

                    ],
                ],
            ],
        ];
    }
}
