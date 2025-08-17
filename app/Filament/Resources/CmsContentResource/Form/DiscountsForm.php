<?php

namespace App\Filament\Resources\CmsContentResource\Form;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;

class DiscountsForm
{
    public static function schema(): array
    {
        return [
            Grid::make(4) // grid de 4 columnas
                ->schema([
                    Textarea::make('component_description')
                        ->label('Descripción componente')
                        ->columnSpan(4), // ocupa todo el ancho
                    // TextInput::make('title')
                    //     ->label('Título sección')
                    //     ->required()
                    //     ->maxLength(100)
                    //     ->columnSpan(4), // ocupa la mitad del row
                    FileUpload::make('image_path')
                        ->label('Imagen')
                        ->helperText('Resolución recomendada: 1536 × 1024 píxeles')
                        ->image()
                        ->directory('about-us')
                        ->visibility('public')
                        ->imageEditor()                        // habilita editor
                        ->imageResizeMode('cover')              // recorta para llenar el tamaño
                        ->imageCropAspectRatio('3:2')          // relación 1000x667 ≈ 3:2
                        ->imageResizeTargetWidth(1536)         // ancho final
                        ->imageResizeTargetHeight(1024),


                    Repeater::make('images')
                        ->label('Descuentos')
                        ->relationship('images') // nombre de la relación en CmsContent
                        ->schema([

                            TextInput::make('title')
                                ->label('Título')
                                ->maxLength(50),
                            TextInput::make('subtitle')
                                ->label('Descripcion corta')
                                ->maxLength(100),
                            TextInput::make('alt_text')
                                ->label('Código descuento')
                                ->maxLength(15),
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
