<?php

namespace App\Filament\CustomWidgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Appointment;
use Illuminate\Support\Carbon;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;

class AppointmentStats extends BaseWidget
{
    protected static bool $shouldRegisterNavigation = false;

    protected function getStats(): array
    {
        $user = Auth::user();
        $currentPanelId = Filament::getCurrentPanel()?->getId();

        // Fechas clave
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfLastWeek = $startOfWeek->copy()->subWeek();

        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfLastMonth = $startOfMonth->copy()->subMonth();
        $endOfLastMonth = $startOfMonth->copy()->subDay();

        // Función para obtener número de citas (con filtro según panel)
        $getAppointments = function ($start, $end = null) use ($currentPanelId, $user) {
            $query = Appointment::query();

            if ($currentPanelId === 'personal' && $user) {
                $query->where('worker_id', $user->id);
            }

            $query->when($end, fn($q) => $q->whereBetween('date', [$start, $end]))
                ->when(!$end, fn($q) => $q->whereDate('date', $start));

            return $query->count();
        };

        // Citas día actual y ayer
        $citasHoy = $getAppointments($today);
        $citasAyer = $getAppointments($yesterday);

        // Citas semana actual y pasada
        $citasSemana = $getAppointments($startOfWeek, Carbon::now());
        $citasSemanaPasada = $getAppointments($startOfLastWeek, $startOfWeek->copy()->subDay());

        // Citas mes actual y pasado
        $citasMes = $getAppointments($startOfMonth, Carbon::now());
        $citasMesPasado = $getAppointments($startOfLastMonth, $endOfLastMonth);

        return [
            Stat::make('Citas de hoy', $citasHoy . " (Confirmadas)")
                ->description("Ayer: " . $citasAyer)
                ->descriptionIcon($citasHoy - $citasAyer > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($citasHoy - $citasAyer > 0 ? 'success' : 'danger')
                ->chart($this->getDailyAppointments($yesterday, $currentPanelId, $user)),

            Stat::make('Citas de esta semana', $citasSemana . " (Confirmadas)")
                ->description("Semana pasada: " . $citasSemanaPasada)
                ->descriptionIcon($citasSemana - $citasSemanaPasada > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($citasSemana - $citasSemanaPasada > 0 ? 'success' : 'danger')
                ->chart($this->getDailyAppointments($startOfWeek, $currentPanelId, $user)),

            Stat::make('Citas de este mes', $citasMes . " (Confirmadas)")
                ->description("Mes pasado: " . $citasMesPasado)
                ->descriptionIcon($citasMes - $citasMesPasado > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($citasMes - $citasMesPasado > 0 ? 'success' : 'danger')
                ->chart($this->getDailyAppointments($startOfMonth, $currentPanelId, $user)),
        ];
    }

    private function getDailyAppointments(Carbon $startDate, ?string $currentPanelId, $user): array
    {
        $endDate = Carbon::today();

        $appointmentsByDay = [];

        if ($startDate->gt($endDate)) {
            return $appointmentsByDay;
        }

        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-m-d');

            $query = Appointment::query()->statusConfirmed()
                ->whereDate('date', $dateString);

            if ($currentPanelId === 'personal' && $user) {
                $query->where('worker_id', $user->id);
            }

            $appointmentsByDay[] = $query->count();

            $currentDate->addDay();
        }

        return $appointmentsByDay;
    }
}
