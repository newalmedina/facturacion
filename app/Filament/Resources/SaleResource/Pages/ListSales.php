<?php

namespace App\Filament\Resources\SaleResource\Pages;


use App\Filament\Resources\SaleResource;
use App\Filament\Resources\SaleResource\Widgets\SalesStats;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListSales extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = SaleResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            SalesStats::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
