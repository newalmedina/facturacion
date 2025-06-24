<?php

namespace App\Filament\CustomWidgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\OtherExpense;
use Illuminate\Support\Carbon;

class OtherExpensesStats extends BaseWidget
{
    protected static bool $shouldRegisterNavigation = false;
    protected function getStats(): array
    {
        // Fechas clave
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfLastWeek = $startOfWeek->copy()->subWeek();

        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfLastMonth = $startOfMonth->copy()->subMonth();
        $endOfLastMonth = $startOfMonth->copy()->subDay();

        // Función para obtener suma total de gastos entre fechas
        $getExpenses = fn($start, $end = null) => OtherExpense::query()
            ->when($end, fn($q) => $q->whereBetween('date', [$start, $end]))
            ->when(!$end, fn($q) => $q->whereDate('date', $start))
            ->get()
            ->sum(fn($expense) => $expense->total);

        // Gastos día actual y ayer
        $expensesToday = $getExpenses($today);
        $expensesYesterday = $getExpenses($yesterday);

        // Gastos semana actual y pasada
        $expensesThisWeek = $getExpenses($startOfWeek, Carbon::now());
        $expensesLastWeek = $getExpenses($startOfLastWeek, $startOfWeek->copy()->subDay());

        // Gastos mes actual y pasado
        $expensesThisMonth = $getExpenses($startOfMonth, Carbon::now());
        $expensesLastMonth = $getExpenses($startOfLastMonth, $endOfLastMonth);

        return [
            Stat::make('Otros gastos en el día de hoy', '€' . number_format($expensesToday, 2))
                ->description("Otros gastos ayer €" . number_format($expensesYesterday, 2))
                ->descriptionIcon($expensesToday - $expensesYesterday > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($expensesToday - $expensesYesterday > 0 ? 'danger' : 'success') // Más gastos = rojo
                ->chart($this->getDailyExpenses($yesterday)),

            Stat::make('Otros gastos en esta semana', '€' . number_format($expensesThisWeek, 2))
                ->description("Otros gastos semana pasada €" . number_format($expensesLastWeek, 2))
                ->descriptionIcon($expensesThisWeek - $expensesLastWeek > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($expensesThisWeek - $expensesLastWeek > 0 ? 'danger' : 'success')
                ->chart($this->getDailyExpenses($startOfWeek)),

            Stat::make('Otros gastos en este mes', '€' . number_format($expensesThisMonth, 2))
                ->description("Otros gastos mes pasado €" . number_format($expensesLastMonth, 2))
                ->descriptionIcon($expensesThisMonth - $expensesLastMonth > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($expensesThisMonth - $expensesLastMonth > 0 ? 'danger' : 'success')
                ->chart($this->getDailyExpenses($startOfMonth)),
        ];
    }

    private function getDailyExpenses(Carbon $startDate): array
    {
        $endDate = Carbon::today();

        $expensesByDay = [];

        if ($startDate->gt($endDate)) {
            return $expensesByDay;
        }

        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-m-d');

            $total = OtherExpense::whereDate('date', $dateString)
                ->get()
                ->sum(fn($expense) => $expense->total);

            $expensesByDay[] = $total ?: 0;

            $currentDate->addDay();
        }

        return $expensesByDay;
    }
}
