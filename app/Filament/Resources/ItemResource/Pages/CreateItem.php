<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateItem extends CreateRecord
{
    protected static string $resource = ItemResource::class;
    // Este método se llama antes de crear el registro
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Verificamos si el tipo es 'service', y ponemos ciertos campos a null
        if (isset($data['type']) && $data['type'] === 'service') {
            // Establecemos a null los campos que no deberían estar presentes para 'service'
            $data['brand_id'] = null;
            $data['supplier_id'] = null;
            $data['unit_of_measure_id'] = null;
            $data['amount'] = null;
        }

        return $data;
    }
}
