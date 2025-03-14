<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StateResource\Pages;
use App\Filament\Resources\StateResource\RelationManagers;
use App\Models\State;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StateResource extends Resource
{
    protected static ?string $model = State::class;
    // protected static ?string $navigationLabel = 'Estados';
    protected static ?string $navigationGroup = 'Tablas de sistemas';
    // protected static ?string $navigationLabel = 'Estados';
    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?int $navigationSort = 16;

    public static function getModelLabel(): string
    {
        return 'Estado';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Estados';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label("Nombre")
                    ->required()
                    ->maxLength(100),
                Forms\Components\Select::make('country_id')
                    ->relationship('country', 'name')
                    ->label("País")
                    ->searchable()->preload()
                    ->required()
                    ->searchable()->preload(),

                Forms\Components\Toggle::make('is_active')
                    ->default(true)

                    ->inline(false)
                    ->label("¿Activo?")
                    ->required(),
                // Forms\Components\TextInput::make('latitude')
                //     ->numeric(),
                // Forms\Components\TextInput::make('longitude')
                //     ->numeric(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->join("countries", "countries.id", "=", "states.country_id")
            ->where('countries.is_active', 1);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label("Nombre")
                    ->sortable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->numeric()
                    ->label("País")
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label("¿Activo?"),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label("Fecha creación")
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('d-m-Y h:i');
                    })
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('latitude')
                // Tables\Columns\TextColumn::make('created_at')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('longitude')
                //     ->numeric()
                //     ->sortable(),
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
            'index' => Pages\ListStates::route('/'),
            'create' => Pages\CreateState::route('/create'),
            'edit' => Pages\EditState::route('/{record}/edit'),
        ];
    }
}
