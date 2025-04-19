<?php

namespace App\Filament\Resources\OtherExpenseItemResource\Pages;

use App\Filament\Resources\OtherExpenseItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOtherExpenseItems extends ListRecords
{
    protected static string $resource = OtherExpenseItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
