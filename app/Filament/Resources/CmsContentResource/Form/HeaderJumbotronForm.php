<?php

namespace App\Filament\Resources\CmsContentResource\Form;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;

class HeaderJumbotronForm
{
    public static function schema(): array
    {
        return [
            Grid::make(4) // grid de 4 columnas
                ->schema([
                    Textarea::make('component_description')
                        ->label('Descripción componente')
                        ->columnSpan(4), // ocupa todo el ancho
                    TextInput::make('title')
                        ->label('Nombre del negocio')
                        ->required()
                        ->maxLength(30)
                        ->columnSpan(2), // ocupa la mitad del row
                    Textarea::make('subtitle')
                        ->label('Slogan')
                        ->maxLength(100)
                        ->columnSpan(4), // ocupa todo el ancho
                ]),
        ];
    }
}
