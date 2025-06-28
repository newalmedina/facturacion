<?php

namespace App\Filament\CustomWidgets;

use Filament\Widgets\LineChartWidget;
use App\Models\Order;
use Illuminate\Support\Carbon;

class VentasMensualesChart extends LineChartWidget
{
    protected static ?string $heading = 'Ventas Mensuales por Año';

    protected function getData(): array
    {
        return [
            'datasets' => $this->getMonthlySalesByYear(),
            'labels' => [
                'Ene',
                'Feb',
                'Mar',
                'Abr',
                'May',
                'Jun',
                'Jul',
                'Ago',
                'Sep',
                'Oct',
                'Nov',
                'Dic'
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'enabled' => true,
                    'mode' => 'index',
                    'intersect' => false,
                    'callbacks' => [
                        'label' => \Illuminate\Support\Js::raw(<<<'JS'
                        function(context) {
                            let label = context.dataset.label || "";
                            if (label) {
                                label += ": ";
                            }
                            if (context.parsed.y !== null) {
                                label += "€" + context.parsed.y.toLocaleString("es-ES", {minimumFractionDigits: 2});
                            }
                            return label;
                        }
                    JS),
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Ventas (€)',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Mes',
                    ],
                ],
            ],
        ];
    }


    private function getMonthlySalesByYear(): array
    {
        $orders = Order::sales()
            ->invoiced()
            ->whereYear('date', '>=', now()->year - 2) // últimos 3 años
            ->get()
            ->groupBy(fn($order) => Carbon::parse($order->date)->year)
            ->map(function ($ordersByYear) {
                return $ordersByYear->groupBy(fn($order) => Carbon::parse($order->date)->format('m'))
                    ->map(function ($ordersByMonth) {
                        return $ordersByMonth->sum('total');
                    });
            });

        $chartData = [];

        $months = collect(range(1, 12))->map(fn($m) => str_pad($m, 2, '0', STR_PAD_LEFT));

        $colors = [
            '#3b82f6', // Azul
            '#10b981', // Verde
            '#f59e0b', // Ámbar
            '#ef4444', // Rojo
            '#8b5cf6', // Violeta
            '#ec4899', // Rosa
        ];

        $colorIndex = 0;

        foreach ($orders->sortKeysDesc() as $year => $monthlySales) {
            $chartData[] = [
                'label' => (string) $year,
                'data' => $months->map(fn($month) => round($monthlySales->get($month, 0), 2))->toArray(),
                'borderColor' => $colors[$colorIndex % count($colors)],
                'backgroundColor' => 'transparent',
                'fill' => false,
                'tension' => 0.3,
                'pointRadius' => 4,
                'pointHoverRadius' => 6,
            ];


            $colorIndex++;
        }

        return $chartData;
    }
}
