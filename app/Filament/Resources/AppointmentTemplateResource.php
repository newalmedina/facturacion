<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentTemplateResource\Pages;
use App\Filament\Resources\AppointmentTemplateResource\RelationManagers;
use App\Models\AppointmentTemplate;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;

class AppointmentTemplateResource extends Resource
{
    protected static ?string $model = AppointmentTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Gestión de citas';
    protected static ?int $navigationSort = 54;

    public static function getModelLabel(): string
    {
        return 'Plantilla cita';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Plantillas citas';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Primera fila con los campos principales
                Forms\Components\Grid::make(3)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Nombre de la Plantilla'),
                        Select::make('worker_id')
                            ->label('Trabajador')
                            ->relationship('worker', 'name') // Usa el campo "name" del modelo User
                            ->searchable()->preload(),

                        Toggle::make('active')->inline(false)
                            ->label('Activa'),

                        Toggle::make('is_general')->inline(false)
                            ->label('Plantilla General'),
                    ]),

                // Segunda fila: Repeater ocupa 100%
                Forms\Components\Grid::make(2)
                    ->schema([
                        Repeater::make('slots')
                            ->label('Horarios')->visible(fn(Get $get) => $get('id') !== null)
                            ->schema([
                                CheckboxList::make('days_of_week')
                                    ->label('Días de la Semana')
                                    ->options([
                                        'monday' => 'Lunes',
                                        'tuesday' => 'Martes',
                                        'wednesday' => 'Miércoles',
                                        'thursday' => 'Jueves',
                                        'friday' => 'Viernes',
                                        'saturday' => 'Sábado',
                                        'sunday' => 'Domingo',
                                    ])
                                    ->required()
                                    ->columns(7),

                                Repeater::make('time_ranges')
                                    ->label('Franja Horaria')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TimePicker::make('start_time')
                                                    ->label('Hora de Inicio')
                                                    ->seconds(false)
                                                    ->required(),

                                                TimePicker::make('end_time')
                                                    ->label('Hora de Fin')
                                                    ->seconds(false)
                                                    ->required()
                                                    ->after('start_time'),
                                            ]),
                                    ])
                                    ->columns(2) // <-- Cambiar esto de 1 a 2 permite que se vean dos ítems por fila
                                    ->columnSpan(1) // Esto hace que el repeater en el grid principal use la mitad
                                    ->itemLabel(fn($state) => ($state['start_time'] ?? '--') . ' - ' . ($state['end_time'] ?? '--'))
                                    ->minItems(1)
                                    ->collapsible()
                                    ->reorderable()



                            ])
                            ->columns(1)
                            ->columnSpanFull()
                            ->minItems(1)
                            ->collapsible(),

                    ])
                    ->columns(2), // Esto asegura que el Repeater esté solo en su fila
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nombre')->searchable(),
                Tables\Columns\TextColumn::make('worker.name')
                    ->numeric()
                    ->label('Empleado')   // Etiqueta de la columna
                    ->searchable()        // Se puede buscar en esta columna
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean()
                    ->label("¿Activo?"),
                Tables\Columns\IconColumn::make('is_general')
                    ->boolean()
                    ->label("Plantilla general"),
                TextColumn::make('slots_count')
                    ->counts('slots')
                    ->label('N° de Horarios'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('')->tooltip('Editar'),

                Tables\Actions\DeleteAction::make()->label('')->tooltip('Eliminar')
            ])
            ->bulkActions([
                //  Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointmentTemplates::route('/'),
            'create' => Pages\CreateAppointmentTemplate::route('/create'),
            'edit' => Pages\EditAppointmentTemplate::route('/{record}/edit'),
        ];
    }
}
