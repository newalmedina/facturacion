<?php

namespace App\Filament\Resources\StateResource\Pages;

use App\Filament\Resources\StateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStates extends ListRecords
{
    protected static string $resource = StateResource::class;
    protected static ?string $title = 'Estados';
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
