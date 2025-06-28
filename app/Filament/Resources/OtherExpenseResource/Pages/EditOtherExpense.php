<?php

namespace App\Filament\Resources\OtherExpenseResource\Pages;

use App\Filament\Resources\OtherExpenseResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditOtherExpense extends EditRecord
{
    protected static string $resource = OtherExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.cancel.label'))
            ->url($this->getResource()::getUrl('index'))
            ->color('gray');
    }
}
