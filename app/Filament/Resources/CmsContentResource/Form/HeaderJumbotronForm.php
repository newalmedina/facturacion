<?php

namespace App\Filament\Resources\CmsContentResource\Form;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;

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
                    TextInput::make('secondary_text')
                        ->label('Texto para cita')
                        ->maxLength(50)
                        ->columnSpan(2), // ocupa la mitad del row
                    FileUpload::make('image_path')
                        ->label('Imagen')
                        ->helperText('Resolución recomendada: 1920 × 1280 píxeles')
                        ->image()
                        ->directory('cms-header-jumbotron')
                        ->visibility('public')
                        ->imageEditor()                        // habilita editor
                        ->imageResizeMode('cover')              // recorta para llenar el tamaño
                        ->imageCropAspectRatio('3:2')          // relación 1000x667 ≈ 3:2
                        ->imageResizeTargetWidth(1920)         // ancho final
                        ->imageResizeTargetHeight(1280)
                        ->columnSpan(4),
                    // TextInput::make('title')
                    //     ->label('Título de la imagen')
                    //     ->maxLength(255)->columnSpan(2),
                    // TextInput::make('alt_text')
                    //     ->label('Texto alternativo')
                    //     ->maxLength(255)->columnSpan(2),
                    // Repeater::make('images')
                    //     ->label('Imagen')
                    //     ->relationship('images') // nombre de la relación en CmsContent
                    //     ->schema([
                    //         FileUpload::make('image_path')
                    //             ->label('Imagen')
                    //             ->helperText('Resolución recomendada: 1920 × 1280 píxeles')
                    //             ->image()
                    //             ->directory('cms-header-jumbotron')
                    //             ->visibility('public')->imageEditor(),
                    //         TextInput::make('title')
                    //             ->label('Título de la imagen')
                    //             ->maxLength(255),
                    //         TextInput::make('alt_text')
                    //             ->label('Texto alternativo')
                    //             ->maxLength(255),
                    //     ])

                    //     ->defaultItems(1)
                    //     ->columns(3)
                    //     // ->minItems(1)
                    //     ->maxItems(1)
                    // ->columnSpan(4)

                ]),
        ];
    }
}
