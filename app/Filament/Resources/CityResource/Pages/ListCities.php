<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCities extends ListRecords
{
    protected static string $resource = CityResource::class;
    protected static ?string $title = 'Ciudades';

    public function mount(): void
    {
        parent::mount();

        // Bloquear acceso si no es super admin
        abort_unless(auth()->check() && auth()->user()->super_admin, 403);
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label("Nuevo registro"),
        ];
    }
}
