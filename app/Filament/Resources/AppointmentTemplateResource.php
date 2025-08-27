<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentTemplateResource\Pages;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use App\Models\AppointmentTemplate;
use Filament\Facades\Filament;
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
use Illuminate\Support\Facades\Auth;

class AppointmentTemplateResource extends Resource
{
    protected static ?string $model = AppointmentTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Gestión de citas';
    protected static ?int $navigationSort = 54;


    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = Auth::user();

        // Obtener el ID del panel actual
        $currentPanelId = Filament::getCurrentPanel()?->getId();

        // Filtrar solo si estamos en el panel "personal"
        if ($user && $currentPanelId === 'personal') {
            $query->where(function ($q) use ($user) {
                $q->where('appointment_templates.worker_id', $user->id)
                    ->orWhere('appointment_templates.is_general', true);
            });
        }

        return $query;
    }

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
        $currentPanelId = Filament::getCurrentPanel()?->getId();
        return $form
            ->schema([
                // Primera fila con los campos principales
                Actions::make([
                    Action::make('createOtherExpenseItem')
                        ->label("Duplicar plantilla")
                        ->icon('heroicon-o-clipboard-document')
                        ->color('success')
                        ->form(function () {
                            $currentPanelId = Filament::getCurrentPanel()?->getId();

                            $fields = [
                                TextInput::make('duplicate_name')
                                    ->label('Nombre de la plantilla')
                                    ->required(),

                                Toggle::make('duplicate_active')
                                    ->label('¿Activa?')
                                    ->inline(false),
                            ];

                            // Solo mostrar estos campos si NO es panel personal
                            if ($currentPanelId !== 'personal') {
                                $fields[] = Select::make('duplicate_worker_id')
                                    ->label('Empleado')
                                    ->relationship('worker', 'name', fn($query) => $query->canAppointment())
                                    ->searchable()
                                    ->preload()
                                    ->visible(fn(callable $get) => $get('duplicate_general') === false)
                                    ->dehydrated(fn(callable $get) => $get('duplicate_general') === false)
                                    ->required(fn(callable $get) => $get('duplicate_general') === false)
                                    ->reactive();

                                $fields[] = Toggle::make('duplicate_general')
                                    ->label('¿Plantilla general?')
                                    ->inline(false)
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, $state) {
                                        if ($state) {
                                            $set('duplicate_worker_id', null);
                                        }
                                    });
                            }

                            return $fields;
                        })
                        ->modalHeading('Duplicar plantilla')
                        ->modalSubmitActionLabel('Guardar')
                        ->modalWidth('md')
                        ->action(function (array $data, Get $get) {
                            $currentPanelId = Filament::getCurrentPanel()?->getId();
                            $original = AppointmentTemplate::findOrFail($get('id'));

                            // Forzar valores si el panel es personal
                            $workerId = $currentPanelId === 'personal'
                                ? Auth::id()
                                : ($data['duplicate_worker_id'] ?? null);

                            $isGeneral = $currentPanelId === 'personal'
                                ? false
                                : ($data['duplicate_general'] ?? false);

                            $duplicated = AppointmentTemplate::create([
                                'name'       => $data['duplicate_name'],
                                'worker_id'  => $workerId,
                                'active'     => $data['duplicate_active'],
                                'is_general' => $isGeneral,
                            ]);

                            // Duplicar slots
                            foreach ($original->slots as $slot) {
                                $duplicated->slots()->create([
                                    'day_of_week' => $slot->day_of_week,
                                    'start_time'  => $slot->start_time,
                                    'end_time'    => $slot->end_time,
                                    'group'       => $slot->group,
                                ]);
                            }

                            Notification::make()
                                ->title('Registro duplicado')
                                ->success()
                                ->send();

                            if ($currentPanelId == 'personal') {
                                return redirect()->route('filament.personal.resources.appointment-templates.edit', [
                                    'record' => $duplicated->id,
                                ]);
                            }
                            return redirect()->route('filament.admin.resources.appointment-templates.edit', [
                                'record' => $duplicated->id,
                            ]);
                        })
                ])->visible(fn(Get $get) => $get('id') !== null),
                Forms\Components\Grid::make(3)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Nombre de la Plantilla'),
                        /*Select::make('worker_id')
                            ->label('Trabajador')
                            ->relationship('worker', 'name') // Usa el campo "name" del modelo User
                            ->searchable()->preload()
                            ->visible(fn(callable $get) => $get('is_general') === false)
                            ->dehydrated(fn(callable $get) => $get('is_general') === false) // no enviar valor si está deshabilitado
                            ->required(fn(callable $get) => $get('is_general') === false) // requerido si NO es general
                            ->reactive(), // para que se actualice dinámicamente,*/
                        Select::make('worker_id')
                            ->label('Trabajador')
                            ->relationship('worker', 'name', fn($query) => $query->canAppointment())
                            ->searchable()
                            ->preload()
                            ->visible($currentPanelId !== 'personal') // 👈 no mostrar en panel personal
                            ->dehydrated(fn(callable $get) => $get('is_general') === false)
                            ->required(fn(callable $get) => $get('is_general') === false)
                            ->reactive(),


                        Toggle::make('active')->inline(false)
                            ->label('Activa'),

                        Toggle::make('is_general')
                            ->inline(false)
                            ->label('Plantilla General')
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state) {
                                if ($state) {
                                    $set('worker_id', null);
                                }
                            })
                            ->disabled($currentPanelId == 'personal'), // 👈 no mostrar en panel personal
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

        $currentPanelId = Filament::getCurrentPanel()?->getId();
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
                        /*Select::make('worker_id')
                            ->label('Empleado')
                            ->relationship('worker', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Selecciona un empleado')
                            ->visible(fn(callable $get) => $get('is_general') !== '1')  // visible solo si is_general NO es '1'
                            ->required(fn(callable $get) => $get('is_general') === '0') // requerido solo si is_general es '0'
                            ->reactive(),*/
                        Select::make('worker_id')
                            ->label('Empleado')
                            ->relationship('worker', 'name', fn($query) => $query->canAppointment()) // <--- aplica tu scope
                            ->searchable()
                            ->preload()
                            ->placeholder('Selecciona un empleado')
                            ->visible(fn(callable $get) => $get('is_general') !== '1')   // visible solo si is_general NO es '1'
                            ->required(fn(callable $get) => $get('is_general') === '0')  // requerido solo si is_general es '0'
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
                    ->requiresConfirmation()
                    ->form(function () use ($currentPanelId) {
                        $fields = [
                            TextInput::make('duplicate_name')
                                ->label('Nombre de la plantilla')
                                ->required(),
                        ];

                        if ($currentPanelId !== 'personal') {
                            // Solo se muestran en paneles que NO son personal
                            $fields[] = Select::make('duplicate_worker_id')
                                ->label('Empleado')
                                ->relationship('worker', 'name')
                                ->searchable()
                                ->preload()
                                ->visible(fn(callable $get) => $get('duplicate_general') === false)
                                ->dehydrated(fn(callable $get) => $get('duplicate_general') === false)
                                ->required(fn(callable $get) => $get('duplicate_general') === false)
                                ->reactive();

                            $fields[] = Toggle::make('duplicate_general')
                                ->label('¿Plantilla general?')
                                ->inline(false)
                                ->reactive()
                                ->afterStateUpdated(function (callable $set, $state) {
                                    if ($state) {
                                        $set('duplicate_worker_id', null);
                                    }
                                });
                        }

                        $fields[] = Toggle::make('duplicate_active')
                            ->label('¿Activa?')
                            ->inline(false);

                        return $fields;
                    })
                    ->modalHeading('Duplicar plantilla')
                    ->modalSubmitActionLabel('Duplicar')
                    ->modalWidth('md')
                    ->action(function (array $data, Tables\Actions\Action $action) use ($currentPanelId) {
                        $original = \App\Models\AppointmentTemplate::findOrFail($action->getRecord()->id);

                        // Forzar worker_id e is_general si el panel es personal
                        $workerId = $currentPanelId === 'personal'
                            ? Auth::id()
                            : ($data['duplicate_worker_id'] ?? null);

                        $isGeneral = $currentPanelId === 'personal'
                            ? false
                            : $data['duplicate_general'];

                        $duplicated = \App\Models\AppointmentTemplate::create([
                            'name'      => $data['duplicate_name'],
                            'worker_id' => $workerId,
                            'active'    => $data['duplicate_active'],
                            'is_general' => $isGeneral,
                        ]);

                        foreach ($original->slots as $slot) {
                            $duplicated->slots()->create([
                                'day_of_week' => $slot->day_of_week,
                                'start_time'  => $slot->start_time,
                                'end_time'    => $slot->end_time,
                                'group'       => $slot->group,
                            ]);
                        }

                        Notification::make()
                            ->title('Plantilla duplicada correctamente')
                            ->success()
                            ->send();

                        if ($currentPanelId == 'personal') {
                            return redirect()->route('filament.personal.resources.appointment-templates.edit', [
                                'record' => $duplicated->id,
                            ]);
                        }

                        return redirect()->route('filament.admin.resources.appointment-templates.edit', [
                            'record' => $duplicated->id,
                        ]);
                    }),
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->tooltip('Eliminar')
                    ->visible(function ($record) use ($currentPanelId) {
                        if ($currentPanelId == 'admin') {
                            return true;
                        } else if ($currentPanelId == 'personal' && $record->worker_id == Auth::user()->id) {
                            return true;
                        }
                        return false;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('cambiarEstado')
                    ->label('Cambiar estado')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->color('primary')
                    ->form([
                        \Filament\Forms\Components\Radio::make('estado')
                            ->label('Selecciona el estado')
                            ->options([
                                1 => 'Activar',
                                0 => 'Desactivar',
                            ])
                            ->required(),
                    ])
                    ->action(function (array $data, $records) {
                        $currentPanelId = \Filament\Facades\Filament::getCurrentPanel()?->getId();
                        $userId = \Illuminate\Support\Facades\Auth::id();

                        foreach ($records as $record) {
                            if ($currentPanelId == 'personal' && $record->worker_id === $userId) {
                                $record->update([
                                    'active' => $data['estado'],
                                ]);
                            } else if ($currentPanelId == 'admin') {
                                $record->update([
                                    'active' => $data['estado'],
                                ]);
                            }
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Estado actualizado')
                            ->body('Se ha actualizado el estado de las plantillas')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(), // 👈 Limpia la selección después de ejecutar
            ])
        ;
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
