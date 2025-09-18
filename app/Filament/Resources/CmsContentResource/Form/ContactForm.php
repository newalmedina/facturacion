<?php

namespace App\Filament\Resources\CmsContentResource\Form;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;

class ContactForm
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
                        // ->required()
                        ->maxLength(100)
                        ->columnSpan([
                            'default' => 12, // móvil
                            'md' => 3,       // escritorio
                        ]), // ocupa la mitad del row
                    TextInput::make('secondary_text')
                        ->label('Texto secundario')
                        // ->required()
                        ->maxLength(100)
                        ->columnSpan(2), // ocupa la mitad del row
                    TextInput::make('tertiary_text')
                        ->label('Texto terciario')
                        // ->required()
                        ->maxLength(100)
                        ->columnSpan(2), // ocupa la mitad del row
                    TextInput::make('facebook_url')
                        ->label('Facebook')
                        ->maxLength(150)
                        ->columnSpan(2),

                    TextInput::make('twitter_url')
                        ->label('Twitter')
                        ->maxLength(150)
                        ->columnSpan(2),

                    TextInput::make('instagram_url')
                        ->label('Instagram')
                        ->maxLength(150)
                        ->columnSpan(2),

                    TextInput::make('youtube_url')
                        ->label('YouTube')
                        ->maxLength(150)
                        ->columnSpan(2),

                    TextInput::make('whatsapp_url')
                        ->label('WhatsApp')
                        ->maxLength(150)
                        ->columnSpan(2),



                    Textarea::make('body')
                        ->label('Google maps')
                        ->columnSpan(4)
                        ->rows(6) // define 10 filas de altura
                        ->hint('Pega aquí el iframe de Google Maps')




                ]),
        ];
    }
}
