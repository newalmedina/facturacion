<?php

namespace App\Filament\CustomWidgets;

use App\Models\Order;
use App\Models\OtherExpense;
use App\Models\User;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\BarChartWidget;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class GananciasMensualesChart extends ChartWidget
{
    protected static ?string $heading = 'Ganancias por Mes';
    protected string|int|array $columnSpan = 'full';


    protected static bool $isLazy = false;
    protected static bool $shouldRegisterNavigation = false;

    protected function getType(): string
    {
        return 'bar';
    }
    // Muestra el select con los años disponibles
    protected function getFilters(): ?array
    {
        $years = Order::sales()
            ->invoiced()
            ->selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        $filters = [];

        foreach ($years as $y) {
            $filters[(string) $y] = "Año $y";
        }

        return $filters;
    }


    protected function getData(): array
    {
        $year = $this->filter ?? now()->year;


        $ventasPorMes = Order::sales()
            ->invoiced()
            ->whereYear('date', $year)
            ->get()
            ->groupBy(fn($order) => Carbon::parse($order->date)->format('m'))
            ->map(fn($ordersByMonth) => $ordersByMonth->sum('total'));

        $dataVenta = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthKey = str_pad($i, 2, '0', STR_PAD_LEFT);
            $dataVenta[] = round($ventasPorMes->get($monthKey, 0), 2);
        }

        $gastosPorMes = OtherExpense::query()
            ->whereYear('date', $year)
            ->get()
            ->groupBy(fn($expense) => Carbon::parse($expense->date)->format('m'))
            ->map(fn($expensesByMonth) => $expensesByMonth->sum('total'));

        $dataGastos = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthKey = str_pad($i, 2, '0', STR_PAD_LEFT);
            $dataGastos[] = round($gastosPorMes->get($monthKey, 0), 2);
        }

        // Calcular ganancias = ventas - gastos
        $dataGanancias = [];
        for ($i = 0; $i < 12; $i++) {
            $ganancia = $dataVenta[$i] - $dataGastos[$i];
            $dataGanancias[] = round($ganancia, 2);
        }

        $labels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        return [
            'datasets' => [
                [
                    'label' => "Ventas en $year",
                    'data' => $dataVenta,
                    'backgroundColor' => '#3b82f6',
                ],
                [
                    'label' => "Gastos en $year",
                    'data' => $dataGastos,
                    'backgroundColor' => '#ef4444',
                ],
                [
                    'label' => "Ganancias en $year",
                    'data' => $dataGanancias,
                    'backgroundColor' => '#10b981', // verde para ganancias
                ],
            ],
            'labels' => $labels,
        ];
    }
}
