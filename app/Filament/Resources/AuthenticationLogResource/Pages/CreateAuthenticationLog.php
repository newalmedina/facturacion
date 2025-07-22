<?php

namespace App\Filament\Resources\AuthenticationLogResource\Pages;

use App\Filament\Resources\AuthenticationLogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAuthenticationLog extends CreateRecord
{
    protected static string $resource = AuthenticationLogResource::class;
}
