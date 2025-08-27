<?php

namespace App\Filament\Resources\AppointmentTemplateResource\Pages;

use App\Filament\Resources\AppointmentTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

use Filament\Actions\Action;
use Filament\Facades\Filament;

class CreateAppointmentTemplate extends CreateRecord
{
    protected static string $resource = AppointmentTemplateResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $currentPanelId = Filament::getCurrentPanel()?->getId();
        if ($currentPanelId == "personal") {

            $data['worker_id'] = auth()->id();
            $data['is_general'] = false;
        }

        return $data;
    }
    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->label(__('filament-panels::resources/pages/create-record.form.actions.create.label'))
                ->submit('create'),
        ];
    }
}
