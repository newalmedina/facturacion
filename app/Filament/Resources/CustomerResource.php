<?php

namespace App\Filament\Resources;

use App\Exports\CustomerExport;
use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\City;
use App\Models\Customer;
use App\Models\State;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Illuminate\Support\Collection;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Tablas de sistemas';
    protected static ?int $navigationSort = 9;

    public static function getEloquentQuery(): Builder
    {

        $query = parent::getEloquentQuery();

        $user = Auth::user();
        // Obtener el ID del panel actual

        $query->where('center_id', $user->center?->id ?? -1);

        return $query;
    }
    public static function getModelLabel(): string
    {
        return 'Cliente';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Clientes';
    }

    public static function form(Form $form): Form
    {
        // Llamamos al método separado
        return $form->schema(static::customerFormSchema());
    }

    public static function customerFormSchema(): array
    {
        return [
            Grid::make(12)
                ->schema([
                    // Columna de imagen
                    Section::make()
                        ->columnSpan([
                            'default' => 12,
                            'md' => 3,
                        ])
                        ->schema([
                            FileUpload::make('image')
                                ->image()
                                ->directory('customers')
                                ->visibility('public')
                                ->label('Imagen'),
                        ]),

                    // Información general
                    Section::make('Información general')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 9,
                        ])
                        ->columns([
                            'default' => 1,
                            'md' => 4,
                        ])
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->label('Nombre')
                                ->columnSpan(['default' => 1, 'md' => 2]),

                            TextInput::make('email')
                                ->email()
                                ->required()
                                ->unique()
                                ->maxLength(255)
                                ->columnSpan(['default' => 1, 'md' => 2]),

                            TextInput::make('phone')
                                ->maxLength(255)
                                ->label('Teléfono')
                                ->columnSpan(['default' => 1, 'md' => 2]),

                            DatePicker::make('birth_date')
                                ->label('Fecha nacimiento')
                                ->columnSpan(['default' => 1, 'md' => 2]),

                            TextInput::make('identification')
                                ->columnSpan(['default' => 1, 'md' => 2]),

                            Radio::make('gender')
                                ->label('Género')
                                ->options([
                                    'masc' => 'Masculino',
                                    'fem' => 'Femenino',
                                ])
                                ->inline()
                                ->inlineLabel(false)
                                ->columnSpan(['default' => 1, 'md' => 2]),

                            Select::make('country_id')
                                ->relationship('country', 'name', fn($query) => $query->where('is_active', true))
                                ->searchable()
                                ->label('País')
                                ->preload()
                                ->live()
                                ->afterStateUpdated(fn(Set $set) => [$set('state_id', null), $set('city_id', null)])
                                ->columnSpan(['default' => 1, 'md' => 2]),

                            Select::make('state_id')
                                ->options(fn(Get $get): Collection => State::query()
                                    ->where('country_id', $get('country_id'))
                                    ->pluck('name', 'id'))
                                ->searchable()
                                ->label('Estado')
                                ->preload()
                                ->live()
                                ->afterStateUpdated(fn(Set $set) => $set('city_id', null))
                                ->columnSpan(['default' => 1, 'md' => 2]),

                            Select::make('city_id')
                                ->options(fn(Get $get): Collection => City::query()
                                    ->where('state_id', $get('state_id'))
                                    ->pluck('name', 'id'))
                                ->searchable()
                                ->label('Ciudad')
                                ->preload()
                                ->columnSpan(['default' => 1, 'md' => 2]),

                            TextInput::make('postal_code')
                                ->label('Código postal')
                                ->columnSpan(['default' => 1, 'md' => 2]),

                            TextInput::make('address')
                                ->label('Dirección')
                                ->columnSpan(['default' => 1, 'md' => 4]),

                            Toggle::make('active')
                                ->label('¿Activo?')
                                ->inline(false)
                                ->required()
                                ->default(true)
                                ->columnSpan(['default' => 1, 'md' => 2]),
                        ]),
                ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Imagen')
                    ->size(50) // Tamaño de la imagen en píxeles
                    ->circular() // Hace la imagen circular
                    ->disk('public'), // Especifica el disco 'public'
                // ->location(fn($record) => 'storage/' . $record->image),
                Tables\Columns\TextColumn::make('name')
                    ->label("Nombre")
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label("Email")
                    ->searchable(),
                Tables\Columns\TextColumn::make('identification')
                    ->sortable()
                    ->label("NIF/CIF")
                    ->searchable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->sortable()
                    ->label("País")
                    ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean()
                    ->label("¿Activo?"),

                Tables\Columns\TextColumn::make('state.name')
                    ->sortable()
                    ->label("Estado")
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city.name')
                    ->sortable()
                    ->label("Ciudad")
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label("Fecha creación")
                    ->dateTime()
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('d-m-Y h:i');
                    })
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('')->tooltip('Editar'),

                Tables\Actions\DeleteAction::make()->label('')->tooltip('Eliminar')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
                BulkAction::make('export')->label('Exportar ' . self::getPluralModelLabel())->icon('heroicon-m-arrow-down-tray')
                    ->action(function ($records) {

                        $modelLabel = self::getPluralModelLabel();
                        // Puedes agregar la fecha o cualquier otro dato para personalizar el nombre
                        $fileName = $modelLabel . '-' . now()->format('d-m-Y') . '.xlsx'; // Ejemplo: "Marcas-2025-03-14.xlsx"

                        // Preparamos la consulta para exportar
                        $query = \App\Models\Customer::whereIn('id', $records->pluck('id'));

                        // Llamamos al método Excel::download() y pasamos el nombre dinámico del archivo
                        return Excel::download(new CustomerExport($query), $fileName);
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
