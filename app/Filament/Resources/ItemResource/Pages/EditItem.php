<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use App\Models\Item;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;

class EditItem extends EditRecord
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.cancel.label'))
            ->url($this->getResource()::getUrl('index'))
            ->color('gray');
    }

    // Este método se llama antes de guardar el registro editado
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['type'] === 'service') {
            // Limpiar campos que no se usan en servicios
            $data['brand_id'] = null;
            $data['supplier_id'] = null;
            $data['unit_of_measure_id'] = null;
            $data['amount'] = null;

            // Opcional: recalcular time por si el usuario manipuló solo el campo formateado
            if (!empty($data['time_formatted'])) {
                if (preg_match('/^(\d{1,2}):(\d{2})$/', $data['time_formatted'], $matches)) {
                    $hours = (int) $matches[1];
                    $minutes = (int) $matches[2];
                    $data['time'] = $hours * 60 + $minutes;
                }
            }
        } else {
            // Si es producto, no usar tiempo
            $data['time'] = null;
        }

        return $data;
    }

    // // Este es el método que se llama después de guardar el registro
    // protected function afterSave($record)
    // {
    //     // Si el tipo es 'service', recargamos la página después de guardar
    //     Filament::script(function () {
    //         return 'window.location.reload();';  // Recarga la página
    //     });
    // }
}
