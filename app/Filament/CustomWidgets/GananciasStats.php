<?php

namespace App\Filament\CustomWidgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
use App\Models\OtherExpense;
use Illuminate\Support\Carbon;

class GananciasStats extends BaseWidget
{
    protected static bool $shouldRegisterNavigation = false;

    protected function getStats(): array
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfLastWeek = $startOfWeek->copy()->subWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfLastMonth = $startOfMonth->copy()->subMonth();
        $endOfLastMonth = $startOfMonth->copy()->subDay();

        // Función para obtener ventas entre fechas
        $getVentas = fn($start, $end = null) => Order::sales()
            ->invoiced()
            ->when($end, fn($q) => $q->whereBetween('date', [$start, $end]))
            ->when(!$end, fn($q) => $q->whereDate('date', $start))
            ->get()
            ->sum(fn($order) => $order->total);

        // Función para obtener gastos entre fechas
        $getExpenses = fn($start, $end = null) => OtherExpense::query()
            ->when($end, fn($q) => $q->whereBetween('date', [$start, $end]))
            ->when(!$end, fn($q) => $q->whereDate('date', $start))
            ->get()
            ->sum(fn($expense) => $expense->total);

        // Ganancias = Ventas - Gastos para cada periodo

        $gananciaHoy = $getVentas($today) - $getExpenses($today);
        $gananciaAyer = $getVentas($yesterday) - $getExpenses($yesterday);

        $gananciaSemana = $getVentas($startOfWeek, Carbon::now()) - $getExpenses($startOfWeek, Carbon::now());
        $gananciaSemanaPasada = $getVentas($startOfLastWeek, $startOfWeek->copy()->subDay()) - $getExpenses($startOfLastWeek, $startOfWeek->copy()->subDay());

        $gananciaMes = $getVentas($startOfMonth, Carbon::now()) - $getExpenses($startOfMonth, Carbon::now());
        $gananciaMesPasado = $getVentas($startOfLastMonth, $endOfLastMonth) - $getExpenses($startOfLastMonth, $endOfLastMonth);

        return [
            Stat::make('Ganancias en el día de hoy', '€' . number_format($gananciaHoy, 2))
                ->description("Ganancias ayer €" . number_format($gananciaAyer, 2))
                ->descriptionIcon($gananciaHoy - $gananciaAyer > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($gananciaHoy - $gananciaAyer > 0 ? 'success' : 'danger')
                ->chart($this->getDailyGanancias($yesterday)),

            Stat::make('Ganancias en esta semana', '€' . number_format($gananciaSemana, 2))
                ->description("Ganancias semana pasada €" . number_format($gananciaSemanaPasada, 2))
                ->descriptionIcon($gananciaSemana - $gananciaSemanaPasada > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($gananciaSemana - $gananciaSemanaPasada > 0 ? 'success' : 'danger')
                ->chart($this->getDailyGanancias($startOfWeek)),

            Stat::make('Ganancias en este mes', '€' . number_format($gananciaMes, 2))
                ->description("Ganancias mes pasado €" . number_format($gananciaMesPasado, 2))
                ->descriptionIcon($gananciaMes - $gananciaMesPasado > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($gananciaMes - $gananciaMesPasado > 0 ? 'success' : 'danger')
                ->chart($this->getDailyGanancias($startOfMonth)),
        ];
    }

    private function getDailyGanancias(Carbon $startDate): array
    {
        $endDate = Carbon::today();
        $gananciasByDay = [];

        if ($startDate->gt($endDate)) {
            return $gananciasByDay;
        }

        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-m-d');

            $ventas = Order::sales()
                ->invoiced()
                ->whereDate('date', $dateString)
                ->get()
                ->sum(fn($order) => $order->total);

            $gastos = OtherExpense::whereDate('date', $dateString)
                ->get()
                ->sum(fn($expense) => $expense->total);

            $gananciasByDay[] = $ventas - $gastos;

            $currentDate->addDay();
        }

        return $gananciasByDay;
    }
}
