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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
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
use Maatwebsite\Excel\Facades\Excel;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
  
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Tablas de sistemas';
    protected static ?int $navigationSort = 9;

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

        return $form
        ->schema([
            Grid::make(12) // Definimos un Grid con 12 columnas en total
                ->schema([
                    Section::make()
                        ->columnSpan(3) // Ocupa 2 columnas de las 12 disponibles
                        ->schema([
                            FileUpload::make('image')
                                ->image()
                                ->directory('customers')
                                ->visibility('public')
                                ->label('Imagen'),
                            // Placeholder::make('created_at')
                            //     ->label('Fecha de Creación')
                            //     ->content(fn($get) => Carbon::parse($get('created_at'))->format('d-m-Y H:i')) // Formatea la fecha
                            //     ->hidden(fn($get) => !$get('id')), // Solo mostrar en edición

                        ]),

                        Section::make('Información general')
                        ->columnSpan(9) // Ocupa 9 columnas de las 12 disponibles
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->label('Nombre')
                                ->columnSpan(2), // Ocupa 2 columnas
                    
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('phone')
                                ->maxLength(255)
                                ->label('Teléfono')
                                ->columnSpan(2),
                    
                            Forms\Components\DatePicker::make('birth_date')
                            ->label('Fecha nacimiento')
                                ->columnSpan(2),
                    
                                Forms\Components\TextInput::make('identification')->columnSpan(2),
                                Forms\Components\Radio::make('gender')
                                    ->label('Género')
                                    ->options([
                                        'masc' => 'Masculino',
                                        'fem' => 'Femenino',
                                    ])
                                    ->inline()
                                    ->columnSpan(2)
                                    ->inlineLabel(false),
                                    Forms\Components\Select::make('country_id')
                                    ->relationship('country', 'name', function ($query) {
                                        $query->where('is_active', true);  // Filtro para que solo se muestren países activos
                                    })
                                    ->searchable()
                                    ->label("País")
                                    ->preload()
                                    ->live()->columnSpan(2)
                                    ->afterStateUpdated(function (Set $set) {
                                        $set('state_id', null);
                                        $set('city_id', null);
                                    }),
                                Forms\Components\Select::make('state_id')
                                    ->options(fn(Get $get): Collection => State::query()
                                        ->where('country_id', $get('country_id'))
                                        ->pluck('name', 'id'))
                                    ->searchable()
                                    ->label("Estado")->columnSpan(2)
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(fn(Set $set) => $set('city_id', null)),
                                Forms\Components\Select::make('city_id')
                                    ->options(fn(Get $get): Collection => City::query()
                                        ->where('state_id', $get('state_id'))
                                        ->pluck('name', 'id'))
                                    ->searchable()
                                    ->label("Ciudad")->columnSpan(2)
                                    ->preload(),
                                    
                                Forms\Components\TextInput::make('postal_code')
                                    ->label("Código postal")->columnSpan(2),
                                Forms\Components\TextInput::make('address')
                                    ->label("Dirección")
                                    ->columnSpan(4),
                                    Forms\Components\Toggle::make('active')
                                    ->label("¿Activo?")
                                    ->inline(false)
                                    ->columnSpan(2)
                                    ->required(),
                        ])
                        ->columns(4)
                ]),
        ]);
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
                    ->label("Identificacion")
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
                Tables\Actions\EditAction::make()->label(''),

                Tables\Actions\DeleteAction::make()->label('')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                   // Tables\Actions\DeleteBulkAction::make(),
                ]),
                BulkAction::make('export') ->label('Exportar '.self::getPluralModelLabel())->icon('heroicon-m-arrow-down-tray')
                    ->action(function ($records) {
                    
                        $modelLabel = self::getPluralModelLabel();
                        // Puedes agregar la fecha o cualquier otro dato para personalizar el nombre
                        $fileName = $modelLabel . '-' . now()->format('Y-m-d') . '.xlsx'; // Ejemplo: "Marcas-2025-03-14.xlsx"
                        
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
