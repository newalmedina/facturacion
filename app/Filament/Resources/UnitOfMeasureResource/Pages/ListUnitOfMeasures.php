<?php

namespace App\Filament\Resources\UnitOfMeasureResource\Pages;

use App\Filament\Resources\UnitOfMeasureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUnitOfMeasures extends ListRecords
{
    protected static string $resource = UnitOfMeasureResource::class;

    public function mount(): void
    {
        parent::mount();

        // Bloquear acceso si no es super admin
        abort_unless(auth()->check() && auth()->user()->super_admin, 403);
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
