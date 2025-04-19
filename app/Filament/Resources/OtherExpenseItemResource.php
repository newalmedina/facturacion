<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OtherExpenseItemResource\Pages;
use App\Models\OtherExpenseItem;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtherExpenseItemResource extends Resource
{
    use SoftDeletes; // Si usas soft deletes

    protected static ?string $model = OtherExpenseItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard'; // Ícono de carpeta

    protected static ?string $navigationLabel = 'Gastos Extra Item'; // Nombre que aparece en el menú de navegación


    protected static ?string $label = 'Gasto Extra'; // Nombre singular

    protected static ?string $pluralLabel = 'Gastos Extra Items'; // Nombre plural
    protected static ?string $navigationGroup = 'Tablas de sistemas';
    protected static ?int $navigationSort = 8;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            Forms\Components\Toggle::make('active')
                ->label("¿Activo?")
                ->inline(false)
                ->required(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label("Nombre")
                    ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->label("¿Activo?")
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label("Fecha creación")
                    ->dateTime()
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('d-m-Y h:i');
                    })
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label('')->visible(fn($record) =>  $record->canDelete)
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOtherExpenseItems::route('/'),
            /*'create' => Pages\CreateOtherExpenseItem::route('/create'),
            'edit' => Pages\EditOtherExpenseItem::route('/{record}/edit'),*/
        ];
    }
}
