<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected static ?string $title = 'Nuevo usuario';


    /*protected function getCreatedNotificationTitle(): ?string
    {
        return 'Registro guardado correctamente';
    }*/
    // Cambiar la etiqueta del botón "Guardar" (Submit)
    /*protected function getSubmitButtonLabel(): string
    {
        return 'Registrar Usuario';  // Cambiar el texto del botón "Guardar"
    }*/
}
