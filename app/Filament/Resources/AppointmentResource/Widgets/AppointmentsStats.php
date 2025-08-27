<?php

namespace App\Filament\Resources\AppointmentResource\Widgets;

use App\Filament\Resources\AppointmentResource\Pages\ListAppointments;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AppointmentsStats extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListAppointments::class;
    }

    protected function getColumns(): int
    {
        return 4; // esto define 4 stats por fila
    }

    protected function getStats(): array
    {
        $total = $this->getPageTableQuery()->count();

        return [
            Stat::make('Núm. citas', $total)
                ->extraAttributes([
                    'class' => 'flex flex-col justify-center items-center text-center h-full text-green-500'
                ]),
        ];
    }
}
