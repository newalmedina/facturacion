<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentTemplateResource\Pages;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use App\Models\AppointmentTemplate;
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
use Filament\Notifications\Notification;
use Filament\Tables\Filters\Filter;

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
                Actions::make([
                    Action::make('createOtherExpenseItem')
                        ->label("Duplicar plantilla")
                        ->icon('heroicon-o-clipboard-document') // icono de "copiar"
                        ->color('success') // color verde
                        ->action(function (array $data, Get $get) {
                            $original = AppointmentTemplate::findOrFail($get('id'));

                            // Crear la plantilla duplicada
                            $duplicated = AppointmentTemplate::create([
                                'name' => $data['duplicate_name'],
                                'worker_id' => $data['duplicate_worker_id'] ?? null,

                                'active' => $data['duplicate_active'],
                                'is_general' => $data['duplicate_general'],
                            ]);

                            // Duplicar slots
                            foreach ($original->slots as $slot) {
                                $duplicated->slots()->create([
                                    'day_of_week' => $slot->day_of_week,
                                    'start_time' => $slot->start_time,
                                    'end_time' => $slot->end_time,
                                    'group' => $slot->group,
                                ]);
                            }
                            Notification::make()
                                ->title('Registro duplicado ')
                                ->success()
                                ->send();
                            // Redirigir a la vista de edición (ajusta la ruta si usas resource diferente)
                            return redirect()->route('filament.admin.resources.appointment-templates.edit', [
                                'record' => $duplicated->id,
                            ]);
                        })
                        ->form([
                            TextInput::make('duplicate_name')
                                ->label('Nombre de la plantilla')
                                ->required(),

                            Select::make('duplicate_worker_id')
                                ->label('Empleado')
                                ->relationship('worker', 'name')
                                ->searchable()
                                ->preload()
                                ->visible(fn(callable $get) => $get('duplicate_general') === false)

                                ->dehydrated(fn(callable $get) => $get('duplicate_general') === false)    // solo enviar si NO es general
                                ->required(fn(callable $get) => $get('duplicate_general') === false)      // obligatorio si NO es general
                                ->reactive(),

                            Toggle::make('duplicate_active')
                                ->label('¿Activa?')
                                ->inline(false),

                            Toggle::make('duplicate_general')
                                ->label('¿Plantilla general?')
                                ->inline(false)
                                ->reactive()
                                ->afterStateUpdated(function (callable $set, $state) {
                                    if ($state) {
                                        // limpiar el empleado cuando se marca como plantilla general
                                        $set('duplicate_worker_id', null);
                                    }
                                }),

                        ])
                        ->modalHeading('Nuevo Gasto Extra')
                        ->modalSubmitActionLabel('Guardar')
                        ->modalWidth('md'),
                ])->visible(fn(Get $get) => $get('id') !== null),
                Forms\Components\Grid::make(3)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Nombre de la Plantilla'),
                        Select::make('worker_id')
                            ->label('Trabajador')
                            ->relationship('worker', 'name') // Usa el campo "name" del modelo User
                            ->searchable()->preload()
                            ->visible(fn(callable $get) => $get('is_general') === false)
                            ->dehydrated(fn(callable $get) => $get('is_general') === false) // no enviar valor si está deshabilitado
                            ->required(fn(callable $get) => $get('is_general') === false) // requerido si NO es general
                            ->reactive(), // para que se actualice dinámicamente,

                        Toggle::make('active')->inline(false)
                            ->label('Activa'),

                        Toggle::make('is_general')->inline(false)
                            ->label('Plantilla General')->reactive()
                            ->afterStateUpdated(function (callable $set, $state) {
                                if ($state) {
                                    $set('worker_id', null); // limpia el campo si es general
                                }
                            }),
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
                                    ->orderColumn('sort')
                                    ->reorderable()



                            ])
                            ->columns(1)
                            ->columnSpanFull()
                            ->minItems(1)
                            ->orderColumn('sort')
                            ->reorderable()
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
                Filter::make('custom_filter')
                    ->form([
                        Select::make('worker_id')
                            ->label('Empleado')
                            ->relationship('worker', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Selecciona un empleado')
                            ->visible(fn(callable $get) => $get('is_general') !== '1')  // visible solo si is_general NO es '1'
                            ->required(fn(callable $get) => $get('is_general') === '0') // requerido solo si is_general es '0'
                            ->reactive(),

                        Select::make('active')
                            ->label('¿Activo?')
                            ->options([
                                '1' => 'Sí',
                                '0' => 'No',
                            ])
                            ->nullable()
                            ->placeholder('Todos'),

                        Select::make('is_general')
                            ->label('¿Plantilla general?')
                            ->options([
                                '1' => 'Sí',
                                '0' => 'No',
                            ])
                            ->nullable()
                            ->placeholder('Todos')
                            ->reactive() // permite que los cambios en este campo actualicen otros campos
                            ->afterStateUpdated(function (callable $set, $state) {
                                if ($state === '1') {
                                    $set('worker_id', null);  // limpia worker_id si is_general es '1'
                                }
                            }),

                    ])
                    ->indicateUsing(function (array $data): array {
                        $filter = [];

                        if (!empty($data['worker_id'])) {
                            // Si quieres mostrar el ID directamente:
                            $filter['worker_id'] = "Trabajador ID: " . $data['worker_id'];

                            // O si tienes un array $workers para mostrar nombre en vez de ID:
                            // $filter['worker_id'] = "Trabajador: " . ($workers[$data['worker_id']] ?? $data['worker_id']);
                        }

                        if (isset($data['active']) && $data['active'] !== null && $data['active'] !== '') {
                            $filter['active'] = $data['active'] ? 'Activo' : 'Inactivo';
                        }

                        if (isset($data['is_general']) && $data['is_general'] !== null && $data['is_general'] !== '') {
                            $filter['is_general'] = $data['is_general'] ? 'General' : 'No General';
                        }

                        return $filter;
                    })

                    ->query(function ($query, array $data) {
                        if (!empty($data['worker_id'])) {
                            $query->where('worker_id', $data['worker_id']);
                        }

                        if ($data['active'] !== null && $data['active'] !== '') {
                            $query->where('active', $data['active']);
                        }

                        if ($data['is_general'] !== null && $data['is_general'] !== '') {
                            $query->where('is_general', $data['is_general']);
                        }

                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('')->tooltip('Editar'),
                Tables\Actions\Action::make('duplicateTemplate')
                    ->tooltip('Duplicar')
                    ->label('')
                    ->icon('heroicon-o-clipboard-document')
                    ->color('success')
                    ->requiresConfirmation() // Opcional si quieres confirmación antes
                    ->form([
                        TextInput::make('duplicate_name')
                            ->label('Nombre de la plantilla')
                            ->required(),

                        Select::make('duplicate_worker_id')
                            ->label('Empleado')
                            ->relationship('worker', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn(callable $get) => $get('duplicate_general') === false)

                            ->dehydrated(fn(callable $get) => $get('duplicate_general') === false)    // solo enviar si NO es general
                            ->required(fn(callable $get) => $get('duplicate_general') === false)      // obligatorio si NO es general
                            ->reactive(),

                        Toggle::make('duplicate_active')
                            ->label('¿Activa?')
                            ->inline(false),

                        Toggle::make('duplicate_general')
                            ->label('¿Plantilla general?')
                            ->inline(false)
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state) {
                                if ($state) {
                                    // limpiar el empleado cuando se marca como plantilla general
                                    $set('duplicate_worker_id', null);
                                }
                            }),
                    ])
                    ->modalHeading('Duplicar plantilla')
                    ->modalSubmitActionLabel('Duplicar')
                    ->modalWidth('md')
                    ->action(function (array $data, Tables\Actions\Action $action) {
                        $original = \App\Models\AppointmentTemplate::findOrFail($action->getRecord()->id);

                        $duplicated = \App\Models\AppointmentTemplate::create([
                            'name' => $data['duplicate_name'],
                            'worker_id' => $data['duplicate_worker_id'] ?? null,

                            'active' => $data['duplicate_active'],
                            'is_general' => $data['duplicate_general'],
                        ]);

                        foreach ($original->slots as $slot) {
                            $duplicated->slots()->create([
                                'day_of_week' => $slot->day_of_week,
                                'start_time' => $slot->start_time,
                                'end_time' => $slot->end_time,
                                'group' => $slot->group,
                            ]);
                        }

                        Notification::make()
                            ->title('Plantilla duplicada correctamente')
                            ->success()
                            ->send();

                        // Redirige a la edición del nuevo registro
                        return redirect()->route('filament.admin.resources.appointment-templates.edit', [
                            'record' => $duplicated->id,
                        ]);
                    }),
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
