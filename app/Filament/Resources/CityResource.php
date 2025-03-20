<?php

namespace App\Filament\Resources;

use App\Exports\CityExport;
use App\Filament\Resources\CityResource\Pages;
use App\Filament\Resources\CityResource\RelationManagers;
use App\Models\City;
use App\Models\Country;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Forms\Components\TextInput;
use Maatwebsite\Excel\Facades\Excel;

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
               

            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $activeCountries = Country::activos()->pluck( 'id')->toArray();

        return parent::getEloquentQuery()
            ->select('cities.*') // Asegura que solo se seleccionen las columnas de 'cities'
          
            ->where('cities.country_id',$activeCountries);
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
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
                BulkAction::make('export') ->label('Exportar '.self::getPluralModelLabel())->icon('heroicon-m-arrow-down-tray')
                ->action(function ($records) {
                
                    $modelLabel = self::getPluralModelLabel();
                    // Puedes agregar la fecha o cualquier otro dato para personalizar el nombre
                    $fileName = $modelLabel . '-' . now()->format('Y-m-d') . '.xlsx'; // Ejemplo: "Marcas-2025-03-14.xlsx"
                    
                    // Preparamos la consulta para exportar
                    $query = \App\Models\City::whereIn('id', $records->pluck('id'));
                    
                    // Llamamos al método Excel::download() y pasamos el nombre dinámico del archivo
                    return Excel::download(new CityExport($query), $fileName);
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
            'index' => Pages\ListCities::route('/'),
           /* 'create' => Pages\CreateCity::route('/create'),
            'edit' => Pages\EditCity::route('/{record}/edit'),*/
        ];
    }
}
