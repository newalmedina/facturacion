<?php

namespace App\Filament\Resources\CmsContentResource\Form;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;

class PriceCatalogForm
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
                        ->columnSpan(1), // ocupa la mitad del row
                    TextInput::make('subtitle')
                        ->label('Subtítulo')
                        ->maxLength(100)
                        ->columnSpan([
                            'default' => 12, // móvil
                            'md' => 3,       // escritorio
                        ]), // ocupa todo el anch2
                    FileUpload::make('image_path')
                        ->label('Imagen')
                        ->helperText('Resolución recomendada: 600 × 400 píxeles')
                        ->image()
                        ->directory('price-catalog')
                        ->visibility('public')
                        ->imageEditor()
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('3:2')          // relación 1000x667 ≈ 3:2
                        ->imageResizeTargetWidth(600)         // ancho final
                        ->imageResizeTargetHeight(400),


                    Repeater::make('images')
                        ->label('Listado de precios')
                        ->relationship('images') // nombre de la relación en CmsContent
                        ->schema([

                            TextInput::make('title')
                                ->label('Título')
                                ->maxLength(50),
                            TextInput::make('subtitle')
                                ->label('Precio')
                                ->numeric()          // Permite solo números
                                ->maxLength(100),

                            Forms\Components\Toggle::make('active')
                                ->inline(false)
                                ->label("¿Activo?")
                                ->required(),

                        ])
                        ->itemLabel(
                            fn($state) => ($state['title'] ?? '--') . ' - ' .
                                ($state['subtitle'] ?? '--') . ' - ' .
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
