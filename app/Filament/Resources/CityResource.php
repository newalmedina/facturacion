<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\Pages;
use App\Filament\Resources\CityResource\RelationManagers;
use App\Models\City;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Forms\Components\TextInput;

class CityResource extends Resource
{
    protected static ?string $model = City::class;
    // protected static ?string $navigationLabel = 'Ciudades';
    protected static ?string $navigationGroup = 'Tablas de sistemas';
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?int $navigationSort = 17;
    // protected static ?string $navigationLabel = 'Ciudadedsadss';
    public static function getModelLabel(): string
    {
        return 'Ciudad';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Ciudades';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label("Nombre")
                    ->maxLength(100),
                Forms\Components\Select::make('country_id')
                    ->label("País")
                    ->relationship('country', 'name')
                    ->required()
                    //->label("Country")
                    ->searchable()->preload(),
                Forms\Components\Select::make('state_id')
                    ->label("Estado")
                    ->relationship('state', 'name')
                    //->label("State")
                    ->required()
                    ->searchable()->preload(),
                Forms\Components\Toggle::make('is_active')
                    ->default(true)

                    ->inline(false)
                    ->label("¿Activo?")
                    ->required(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label("Fecha creación")
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('d-m-Y h:i');
                    })
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // Forms\Components\TextInput::make('latitude')
                //     ->numeric(),
                // Forms\Components\TextInput::make('longitude')
                //     ->numeric(),

            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->join("countries", "countries.id", "=", "cities.country_id")
            ->where('countries.is_active', 1);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->label("Nombre"),
            Tables\Columns\TextColumn::make('country.name')
                ->numeric()
                ->label("País")
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('state.name')
                ->numeric()
                ->label("Estado")
                ->searchable()
                ->sortable(),

            Tables\Columns\IconColumn::make('is_active')
                ->label("¿Activo?"),

            // Tables\Columns\TextColumn::make('latitude')
            //     ->numeric()
            //     ->sortable(),
            // Tables\Columns\TextColumn::make('longitude')
            //     ->numeric()
            //     ->sortable(),
            // Tables\Columns\TextColumn::make('created_at')
            //     ->dateTime()
            //     ->sortable()
            //     ->toggleable(isToggledHiddenByDefault: true),
            // Tables\Columns\TextColumn::make('updated_at')
            //     ->dateTime()
            //     ->sortable()
            //     ->toggleable(isToggledHiddenByDefault: true),
            // Tables\Columns\TextColumn::make('deleted_at')
            //     ->dateTime()
            //     ->sortable()
            //     ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(''),
                /*Tables\Actions\DeleteAction::make()->label('')->successNotificationTitle('Registro eliminado correctamente')
                    ->modalHeading('Eliminar registro')
                    ->modalDescription('¿Estás seguro de que deseas eliminar este registro?')
                    ->modalSubmitActionLabel('Si, eliminar')
                    ->modalCancelActionLabel('Cancelar') */
                Tables\Actions\DeleteAction::make()->label('')
            ])
            ->bulkActions([
                /*Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),*/]);
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
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCity::route('/create'),
            'edit' => Pages\EditCity::route('/{record}/edit'),
        ];
    }
}
