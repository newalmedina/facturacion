<?php

namespace App\Filament\Resources;

use App\Exports\CountryExport;
use App\Filament\Resources\CountryResource\Pages;
use App\Filament\Resources\CountryResource\RelationManagers;
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
use Maatwebsite\Excel\Facades\Excel;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;
    protected static ?string $navigationGroup = 'Tablas de sistemas';
    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?int $navigationSort = 15;

    public static function getModelLabel(): string
    {
        return 'País';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Paises';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label("Nombre")
                    ->maxLength(100),
                Forms\Components\TextInput::make('iso2')
                    ->required()
                    ->maxLength(2),
                Forms\Components\TextInput::make('iso3')
                    ->required()
                    ->maxLength(3),

                Forms\Components\TextInput::make('phonecode')
                    ->tel()
                    ->label("Código tel")
                    ->maxLength(255),
                Forms\Components\TextInput::make('capital')
                    ->label("Capital")
                    ->maxLength(255),
                Forms\Components\TextInput::make('currency')
                    ->label("Moneda")
                    ->maxLength(255),
                Forms\Components\TextInput::make('region')
                    ->label("Región")
                    ->maxLength(255),
                Forms\Components\TextInput::make('subregion')
                    ->label("Subregión")
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->default(true)

                    ->label("¿Activo?")
                    ->inline(false)
                    ->required(),
                /*Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label("Fecha creación")
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('d-m-Y h:i');
                    })
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),*/
                // Forms\Components\TextInput::make('numeric_code')
                //     ->maxLength(3),
                // Forms\Components\TextInput::make('currency_name')
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('currency_symbol')
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('tld')
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('native')
                //     ->maxLength(255),

                // Forms\Components\Textarea::make('timezones')
                //     ->columnSpanFull(),
                // Forms\Components\Textarea::make('translations')
                //     ->columnSpanFull(),
                // Forms\Components\TextInput::make('latitude')
                //     ->numeric(),
                // Forms\Components\TextInput::make('longitude')
                //     ->numeric(),
                // Forms\Components\TextInput::make('emoji')
                //     ->maxLength(191),
                // Forms\Components\TextInput::make('emojiU')
                //     ->maxLength(191),
                // Forms\Components\Toggle::make('flag')
                // ->required(),

            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_active', 1);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label("Nombre")
                    ->sortable(),
                Tables\Columns\TextColumn::make('iso2')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('iso3')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('phonecode')
                    ->label("Código tel")
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('capital')
                    ->searchable()
                    ->label("Capital")
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable()
                    ->label("Moneda")
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label("¿Activo?"),

                // Tables\Columns\TextColumn::make('numeric_code')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('currency_name')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('currency_symbol')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('tld')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('native')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('region')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('subregion')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('latitude')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('longitude')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('emoji')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('emojiU')
                //     ->searchable(),
                // Tables\Columns\IconColumn::make('flag')
                //     ->boolean(),

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
                    $fileName = $modelLabel . '-' . now()->format('d-m-Y') . '.xlsx'; // Ejemplo: "Marcas-2025-03-14.xlsx"
                    
                    // Preparamos la consulta para exportar
                    $query = \App\Models\Country::whereIn('id', $records->pluck('id'));
                    
                    // Llamamos al método Excel::download() y pasamos el nombre dinámico del archivo
                    return Excel::download(new CountryExport($query), $fileName);
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
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }
}
