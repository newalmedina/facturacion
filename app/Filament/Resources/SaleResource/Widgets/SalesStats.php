<?php

namespace App\Filament\Resources\SaleResource\Widgets;

use App\Filament\Resources\SaleResource\Pages\ListSales;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesStats extends StatsOverviewWidget
{
    use InteractsWithPageTable;
    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListSales::class;
    }
    protected function getColumns(): int
    {
        return 4; // esto define 2 stats por fila
    }

    protected function getStats(): array
    {
        $totalTransactions = $this->getPageTableQuery()->count();
        $totalAmount = $this->getPageTableQuery()->get()->sum(function ($expense) {
            return $expense->total; // Usa el accesor
        });

        return [
            Stat::make('Total gastos', '€ ' . number_format($totalAmount, 2))
                ->extraAttributes(['class' => 'flex flex-col justify-center items-center text-center h-full text-green-500']),
            Stat::make('Núm. transacciones', $totalTransactions)
                ->extraAttributes(['class' => 'flex flex-col justify-center items-center text-center h-full text-green-500']),
        ];
    }
}
