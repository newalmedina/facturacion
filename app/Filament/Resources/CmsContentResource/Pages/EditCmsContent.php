<?php

namespace App\Filament\Resources\CmsContentResource\Pages;

use App\Filament\Resources\CmsContentResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditCmsContent extends EditRecord
{
    protected static string $resource = CmsContentResource::class;

    public function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.cancel.label'))
            ->url($this->getResource()::getUrl('index'))
            ->color('gray');
    }

    // Cambiado a public
    public function getTitle(): string
    {
        $slug = $this->record?->slug; // obtiene el slug del registro
        return $slug ? 'Editar ' . $slug : 'Editar cms';
    }
}
