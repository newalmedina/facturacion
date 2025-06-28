<?php

namespace App\Filament\CustomWidgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
use Illuminate\Support\Carbon;

class VentasStats extends BaseWidget
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

        // Función para obtener suma total de ventas entre fechas
        $getVentas = fn($start, $end = null) => Order::sales()
            ->invoiced()
            ->when($end, fn($q) => $q->whereBetween('date', [$start, $end]))
            ->when(!$end, fn($q) => $q->whereDate('date', $start))
            ->get()
            ->sum(fn($order) => $order->total);

        // Ventas día actual y ayer
        $ventasHoy = $getVentas($today);
        $ventasAyer = $getVentas($yesterday);

        // Ventas semana actual y pasada
        $ventasSemana = $getVentas($startOfWeek, Carbon::now());
        $ventasSemanaPasada = $getVentas($startOfLastWeek, $startOfWeek->copy()->subDay());

        // Ventas mes actual y pasado
        $ventasMes = $getVentas($startOfMonth, Carbon::now());
        $ventasMesPasado = $getVentas($startOfLastMonth, $endOfLastMonth);


        return [
            Stat::make('Ventas (facturadas) en el día de hoy', '€' . number_format($ventasHoy, 2))
                ->description("Ventas ayer €" . number_format($ventasAyer, 2))
                ->descriptionIcon($ventasHoy - $ventasAyer > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($ventasHoy - $ventasAyer > 0 ? 'success' : 'danger')
                ->chart($this->getDailySales($yesterday)),

            Stat::make('Ventas (facturadas) en esta semana', '€' . number_format($ventasSemana, 2))
                ->description("Ventas semana pasada €" . number_format($ventasSemanaPasada, 2))
                ->descriptionIcon($ventasSemana - $ventasSemanaPasada > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($ventasSemana - $ventasSemanaPasada > 0 ? 'success' : 'danger')
                ->chart($this->getDailySales($startOfWeek)),

            Stat::make('Ventas (facturadas) en este mes', '€' . number_format($ventasMes, 2))
                ->description("Ventas mes pasado €" . number_format($ventasMesPasado, 2))
                ->descriptionIcon($ventasMes - $ventasMesPasado > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($ventasMes - $ventasMesPasado > 0 ? 'success' : 'danger')
                ->chart($this->getDailySales($startOfMonth)),
        ];
    }

    private function getDailySales(Carbon $startDate): array
    {
        $endDate = Carbon::today();
        $getVentas = fn($start, $end = null) => Order::sales()
            ->invoiced()
            ->when($end, fn($q) => $q->whereBetween('date', [$start, $end]))
            ->when(!$end, fn($q) => $q->whereDate('date', $start))
            ->get()
            ->sum(fn($order) => $order->total);

        $salesByDay = [];

        if ($startDate->gt($endDate)) {
            return $salesByDay;
        }

        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-m-d');

            $total = $getVentas($dateString);

            // Garantizar que haya un valor numérico, 0 si no hay ventas
            $salesByDay[] = $total ?: 0;

            $currentDate->addDay();
        }

        return $salesByDay;
    }
}
