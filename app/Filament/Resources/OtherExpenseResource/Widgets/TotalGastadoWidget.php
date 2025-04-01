<?php

namespace App\Filament\Resources\OtherExpenseResource\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalGastadoWidget extends StatsOverviewWidget
{ 
    public ?int $total = 0; // Propiedad pública para almacenar el monto

    protected function getStats(): array
    {
        return [
            Stat::make('Total Gastado', '$' . number_format($this->total, 2))
                ->icon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }
}
