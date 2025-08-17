<?php

namespace App\Filament\Resources\CmsContentResource\Form;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;

class ServicesForm
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
                        ->label('Título sección')
                        ->required()
                        ->maxLength(100)
                        ->columnSpan(4), // ocupa la mitad del row



                    Repeater::make('images')
                        ->label('Servicios')
                        ->relationship('images') // nombre de la relación en CmsContent
                        ->schema([
                            FileUpload::make('image_path')
                                ->label('Imagen')
                                ->helperText('Resolución recomendada: 1000 × 667 píxeles')
                                ->image()
                                ->directory('services')
                                ->visibility('public')
                                ->imageEditor()                        // habilita editor
                                ->imageResizeMode('cover')              // recorta para llenar el tamaño
                                ->imageCropAspectRatio('3:2')          // relación 1000x667 ≈ 3:2
                                ->imageResizeTargetWidth(1000)         // ancho final
                                ->imageResizeTargetHeight(667),      // alto final

                            TextInput::make('title')
                                ->label('Título')
                                ->maxLength(30),
                            TextInput::make('alt_text')
                                ->label('Precio')
                                ->maxLength(20),
                            Forms\Components\Toggle::make('active')
                                ->inline(false)
                                ->label("¿Activo?")
                                ->required(),

                        ])
                        ->itemLabel(
                            fn($state) => ($state['title'] ?? '--') . ' - ' .
                                ($state['alt_text'] ?? '--') . ' - ' .
                                (($state['active'] ?? false) ? 'Activo' : 'Inactivo')
                        )
                        ->cloneable()
                        ->collapsed()
                        ->collapsible()
                        ->columns(3)
                        ->reorderable()
                        ->columnSpan(4)

                ]),
        ];
    }
}
