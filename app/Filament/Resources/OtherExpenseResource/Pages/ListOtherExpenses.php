<?php

namespace App\Filament\Resources\OtherExpenseResource\Pages;

use App\Filament\Resources\OtherExpenseResource;
use App\Filament\Resources\OtherExpenseResource\Widgets\OtherExpenseStats;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListOtherExpenses extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = OtherExpenseResource::class;    
    
    protected function getHeaderWidgets(): array
    {
        return [
            OtherExpenseStats::class,
        ];
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
