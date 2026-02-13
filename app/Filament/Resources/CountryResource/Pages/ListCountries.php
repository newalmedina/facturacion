<?php

namespace App\Filament\Resources\CountryResource\Pages;

use App\Filament\Resources\CountryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCountries extends ListRecords
{
    protected static string $resource = CountryResource::class;
    protected static ?string $title = 'Paises';
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
