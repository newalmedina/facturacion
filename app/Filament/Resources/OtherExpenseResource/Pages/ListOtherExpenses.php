<?php

namespace App\Filament\Resources\OtherExpenseResource\Pages;

use App\Filament\Resources\OtherExpenseResource;
use App\Filament\Resources\OtherExpenseResource\Widgets\TotalGastadoWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOtherExpenses extends ListRecords
{
    protected static string $resource = OtherExpenseResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            TotalGastadoWidget::class => [
                'total' => 1000, // Pasamos el valor aquí
            ],
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
