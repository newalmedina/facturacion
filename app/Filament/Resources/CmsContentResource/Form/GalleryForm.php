<?php

namespace App\Filament\Resources\CmsContentResource\Form;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;

class GalleryForm
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
                        ->label('Título')
                        ->required()
                        ->maxLength(30)
                        ->columnSpan(2),
                    TextInput::make('subtitle')
                        ->label('Subtítulo')
                        // ->required()
                        ->maxLength(150)
                        ->columnSpan(2),
                    // ->helperText('En la home solo se mostrarán las 5 primeras fotos'), // ocupa la mitad del row
                    // Textarea::make('subtitle')
                    //     ->label('Subtítulo')
                    //     ->maxLength(100)
                    //     ->columnSpan(4), // ocupa todo el ancho

                    Repeater::make('images')
                        ->label('Imágenes')
                        ->relationship('images') // nombre de la relación en CmsContent
                        ->schema([
                            FileUpload::make('image_path')
                                ->label('Imagen')
                                ->helperText('Resolución recomendada: 666 × 579 píxeles')
                                ->image()
                                ->directory('about-us')
                                ->visibility('public')
                                ->required()
                                ->imageEditor()                        // habilita editor
                                ->imageResizeMode('cover')              // recorta para llenar el tamaño
                                ->imageCropAspectRatio('23:20')
                                ->imageResizeTargetWidth(666)
                                ->imageResizeTargetHeight(579),
                            TextInput::make('title')
                                ->label('Nombre (opcional, solo posicionamiento)')
                                ->maxLength(15),
                            TextInput::make('alt_text')
                                ->label('Descripcion corta (opcional, solo posicionamiento)')
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
                        ->orderColumn('sort')
                        ->reorderable()
                        ->columnSpan(4)

                ]),
        ];
    }
}
