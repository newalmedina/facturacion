<?php

namespace App\Filament\Resources;

use App\Exports\AppointmentExport;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Maatwebsite\Excel\Facades\Excel;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;


    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationGroup = 'Gestión de citas';
    protected static ?int $navigationSort = 50;
    // protected static ?string $navigationLabel = 'Ciudadedsadss';
    public static function getModelLabel(): string
    {
        return 'Cita';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Citas';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('user_id')
                //     ->required()
                //     ->numeric(),
                Select::make('worker_id')
                    ->label('Trabajador')
                    ->relationship('worker', 'name') // Usa el campo "name" del modelo User
                    ->searchable()->preload()
                    ->required(),

                DatePicker::make('date')
                    ->label('Fecha')
                    ->required(),

                TimePicker::make('start_time')
                    ->label('Hora de inicio')->seconds(false)
                    ->required(),

                TimePicker::make('end_time')
                    ->label('Hora de fin')->seconds(false)
                    ->required(),

                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'confirmed' => 'Confirmado',
                        //'accepted' => 'Aceptada',
                        'cancelled' => 'Cancelada',
                    ])
                    ->nullable()
                    ->default(null)
                    ->placeholder('Sin estado'),

                TextInput::make('requester_email')
                    ->label('Correo del solicitante')
                    ->email()
                    ->maxLength(255),

                TextInput::make('requester_phone')
                    ->label('Teléfono del solicitante')
                    ->tel()
                    ->maxLength(255),

                Textarea::make('comments')
                    ->label('Comentarios')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('active')
                    ->inline(false)
                    ->label("¿Activo?")
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('user_id')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('worker.name')
                    ->numeric()
                    ->label('Empleado')   // Etiqueta de la columna
                    ->searchable()        // Se puede buscar en esta columna
                    ->sortable(),         // Se puede ordenar por esta columna

                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->label('Fecha')      // Etiqueta de la columna
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('d-m-Y'); // Formato día-mes-año
                    }),

                Tables\Columns\TextColumn::make('start_time')
                    ->date()
                    ->label('Hora inicio')  // Etiqueta
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('H:i'); // Formato hora:minutos (24h)
                    }),

                Tables\Columns\TextColumn::make('end_time')
                    ->date()
                    ->label('Hora final')   // Etiqueta
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('H:i'); // Formato hora:minutos (24h)
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(function (?string $state) {
                        return match ($state) {
                            'pending' => 'Pendiente',
                            'confirmed' => 'Confirmado',
                            //'accepted' => 'Aceptada',
                            'cancelled' => 'Cancelada',
                            null, '' => 'Sin estado',
                            default => $state,
                        };
                    }),

                Tables\Columns\TextColumn::make('requester_email')->label("Correo solicitante")
                    ->searchable(),   // Buscable

                Tables\Columns\TextColumn::make('requester_phone')->label("telefono solicitante")
                    ->searchable(),   // Buscable

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Se puede ocultar/mostrar por defecto oculto
                Tables\Columns\IconColumn::make('active')
                    ->boolean()
                    ->label("¿Activo?"),
                Tables\Columns\TextColumn::make('template.name')
                    ->numeric()
                    ->label('Plantilla')   // Etiqueta de la columna
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)       // Se puede buscar en esta columna
                    ->sortable(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true), // Comentado (columna actualizada)

            ])
            ->filters([
                Filter::make('filter_appointment')
                    ->form([
                        Select::make('worker_id')
                            ->label('Empleado')
                            ->relationship('worker', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Selecciona empleado'),

                        DatePicker::make('date_from')
                            ->label('Fecha inicio'),

                        DatePicker::make('date_until')
                            ->label('Fecha fin'),

                        Select::make('status')
                            ->label('Estado')
                            ->options([
                                'pending' => 'Pendiente',
                                'confirmed' => 'Confirmado',
                                //'accepted' => 'Aceptada',
                                'cancelled' => 'Cancelada',
                            ])
                            ->nullable()
                            ->placeholder('Sin estado'),
                    ])
                    ->query(function ($query, array $data) {
                        if (!empty($data['worker_id'])) {
                            $query->where('worker_id', $data['worker_id']);
                        }

                        if (!empty($data['date_from'])) {
                            $query->whereDate('date', '>=', $data['date_from']);
                        }

                        if (!empty($data['date_until'])) {
                            $query->whereDate('date', '<=', $data['date_until']);
                        }

                        if (isset($data['status']) && $data['status'] !== null) {
                            $query->where('status', $data['status']);
                        }

                        return $query;
                    }),
            ])

            ->actions([
                Tables\Actions\EditAction::make()->label('')->tooltip('Editar'),
                Tables\Actions\DeleteAction::make()->label('')->tooltip('Eliminar')->visible(fn($record) => $record->status === null || $record->status === 'cancelled'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),

                ]),
                BulkAction::make('export')->label('Exportar ' . self::getPluralModelLabel())->icon('heroicon-m-arrow-down-tray')
                    ->action(function ($records) {

                        $modelLabel = self::getPluralModelLabel();
                        // Puedes agregar la fecha o cualquier otro dato para personalizar el nombre
                        $fileName = $modelLabel . '-' . now()->format('d-m-Y') . '.xlsx'; // Ejemplo: "Marcas-2025-03-14.xlsx"

                        // Preparamos la consulta para exportar
                        $query = \App\Models\Appointment::whereIn('id', $records->pluck('id'));

                        // Llamamos al método Excel::download() y pasamos el nombre dinámico del archivo
                        return Excel::download(new AppointmentExport($query), $fileName);
                    }),
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
            'index' => Pages\ListAppointments::route('/'),
            // 'create' => Pages\CreateAppointment::route('/create'),
            // 'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
