<?php

namespace App\Filament\Resources\AppointmentTemplateResource\Pages;

use App\Filament\Resources\AppointmentTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

use Filament\Actions\Action;

class CreateAppointmentTemplate extends CreateRecord
{
    protected static string $resource = AppointmentTemplateResource::class;
    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->label(__('filament-panels::resources/pages/create-record.form.actions.create.label'))
                ->submit('create'),
        ];
    }
}
