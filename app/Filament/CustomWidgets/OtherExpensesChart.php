<?php

namespace App\Filament\CustomWidgets;

use Filament\Widgets\LineChartWidget;
use App\Models\OtherExpense;  // Cambia al modelo correcto
use Illuminate\Support\Carbon;

class OtherExpensesChart extends LineChartWidget
{
    protected static ?string $heading = 'Gastos Mensuales por Año';

    protected function getData(): array
    {
        $monthlyExpenses = $this->getMonthlyExpensesByYear();

        return [
            'datasets' => $monthlyExpenses,
            'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        ];
    }

    private function getMonthlyExpensesByYear(): array
    {
        $expenses = OtherExpense::query()
            ->whereYear('date', '>=', now()->year - 2)  // últimos 3 años (puedes ajustar)
            ->get()
            ->groupBy(fn($expense) => Carbon::parse($expense->date)->year)
            ->map(function ($expensesByYear) {
                return $expensesByYear->groupBy(fn($expense) => Carbon::parse($expense->date)->format('m'))
                    ->map(fn($expensesByMonth) => $expensesByMonth->sum('total'));
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
        foreach ($expenses as $year => $monthlyExpenses) {
            $chartData[] = [
                'label' => (string) $year,
                'data' => $months->map(fn($month) => round($monthlyExpenses->get($month, 0), 2))->toArray(),
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
                    'position' => 'top',
                    'labels' => [
                        'usePointStyle' => false,  // cuadrados en leyenda
                    ],
                ],
            ],
        ];
    }
}
