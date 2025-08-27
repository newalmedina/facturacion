<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $currentPanelId = Filament::getCurrentPanel()?->getId();
        if ($currentPanelId == "personal") {

            $data['worker_id'] = auth()->id();
        }

        return $data;
    }
}
