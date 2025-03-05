<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCities extends ListRecords
{
    protected static string $resource = CityResource::class;
    protected static ?string $title = 'Ciudades';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label("Nuevo registro"),
        ];
    }
}
