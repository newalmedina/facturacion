<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCity extends CreateRecord
{
    protected static string $resource = CityResource::class;
    protected static ?string $title = 'Crear ciudad';/*protected function getCreatedNotificationTitle(): ?string
    {
        return 'Registro guardado correctamente';
    }*/
}
