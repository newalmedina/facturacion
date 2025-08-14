<?php

namespace App\Filament\Resources\CmsContentResource\Pages;

use App\Filament\Resources\CmsContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCmsContents extends ListRecords
{
    protected static string $resource = CmsContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
