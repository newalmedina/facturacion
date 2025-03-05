<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use App\Models\City;
use App\Models\State;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\ImageColumn;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Tablas de sistemas';
    protected static ?int $navigationSort = 13;
    public static function getModelLabel(): string
    {
        return 'Proveedor';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Proveedores';
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
                                    ->directory('suppliers')
                                    ->visibility('public')
                                    ->label('Imagen'),

                            ]),

                        Grid::make(9)
                            ->columnSpan(9)
                            ->schema([
                                Section::make('Información general')
                                    ->columns(2)
                                    ->schema([

                                        Forms\Components\TextInput::make('name')
                                            ->label("Nombre")
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\Textarea::make('description')
                                            ->label("Descripción")
                                            ->columnSpanFull(),
                                        Forms\Components\Select::make('country_id')
                                            ->relationship('country', 'name', function ($query) {
                                                $query->where('is_active', true);  // Filtro para que solo se muestren países activos
                                            })
                                            ->searchable()
                                            ->label("País")
                                            ->preload()
                                            ->live()
                                            ->afterStateUpdated(function (Set $set) {
                                                $set('state_id', null);
                                                $set('city_id', null);
                                            }),
                                        Forms\Components\Select::make('state_id')
                                            ->options(fn(Get $get): Collection => State::query()
                                                ->where('country_id', $get('country_id'))
                                                ->pluck('name', 'id'))
                                            ->searchable()
                                            ->label("Estado")
                                            ->preload()
                                            ->live()
                                            ->afterStateUpdated(fn(Set $set) => $set('city_id', null)),
                                        Forms\Components\Select::make('city_id')
                                            ->options(fn(Get $get): Collection => City::query()
                                                ->where('state_id', $get('state_id'))
                                                ->pluck('name', 'id'))
                                            ->searchable()
                                            ->label("Ciudad")
                                            ->preload(),
                                        Forms\Components\TextInput::make('address')
                                            ->label("Dirección")
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('postal_code')
                                            ->label("Código postal")
                                            ->maxLength(255),
                                        Forms\Components\Toggle::make('active')
                                            ->label("Activo")
                                            ->required(),


                                    ]),
                                Section::make('Información de contacto')
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('contact_name')
                                            ->label("Nombre")
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('contact_identification')
                                            ->label("Identificación")
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('contact_email')
                                            ->email()
                                            ->label("Email")
                                            // ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('contact_phone')
                                            ->label("Teléfono")
                                            // ->tel()
                                            ->maxLength(255),

                                    ]) // Solo visible al editar (cuando 'id' está presente)

                            ])
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
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label("Nombre")
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_email')
                    ->label("Email del contacto")
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_name')
                    ->label("Nombre del contacto")
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_identification')
                    ->label("Identificación del contacto")
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_phone')
                    ->label("Teléfono de contacto")
                    ->searchable(),
                // Tables\Columns\TextColumn::make('country_id')
                //     ->label("País")
                //     ->searchable()
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('state_id')
                //     ->label("estado")
                //     ->searchable()
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('city_id')
                //     ->label("Ciudad")
                //     ->numeric()
                //     ->searchable()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('address')
                //     ->label("Dirección")
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('postal_code')
                //     ->label("Código postal")
                //     ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->label("Activo")
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label("Fecha creación")
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('d-m-Y h:i');
                    })
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label('')
            ])
            /* ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])*/;
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
