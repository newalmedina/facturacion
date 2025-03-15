<?php

namespace App\Filament\Resources;

use App\Exports\UnitOfMeasureExport;
use App\Filament\Resources\UnitOfMeasureResource\Pages;
use App\Filament\Resources\UnitOfMeasureResource\RelationManagers;
use App\Models\UnitOfMeasure;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Actions\BulkAction;

class UnitOfMeasureResource extends Resource
{
    protected static ?string $model = UnitOfMeasure::class;

    protected static ?string $navigationIcon = 'fas-ruler';
    protected static ?string $navigationGroup = 'Tablas de sistemas';
    protected static ?int $navigationSort = 14;
    public static function getModelLabel(): string
    {
        return 'Unidad de medida';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Unidades de medidas';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label("Nombre")
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label("Descripción")
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('active')
                    ->label("Activo")
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label("Nombre")
                    ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->label("Activo")
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label("Fecha creación")
                    ->dateTime()
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
             ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
               /* Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),*/
                
            ]),
            BulkAction::make('export') ->label('Exportar '.self::getPluralModelLabel())->icon('heroicon-m-arrow-down-tray')
                ->action(function ($records) {
                
                    $modelLabel = self::getPluralModelLabel();
                    // Puedes agregar la fecha o cualquier otro dato para personalizar el nombre
                    $fileName = $modelLabel . '-' . now()->format('Y-m-d') . '.xlsx'; // Ejemplo: "Marcas-2025-03-14.xlsx"
                    
                    // Preparamos la consulta para exportar
                    $query = \App\Models\UnitOfMeasure::whereIn('id', $records->pluck('id'));
                    
                    // Llamamos al método ExcelBrandExport::download() y pasamos el nombre dinámico del archivo
                    return Excel::download(new UnitOfMeasureExport($query), $fileName);
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
            'index' => Pages\ListUnitOfMeasures::route('/'),
            'create' => Pages\CreateUnitOfMeasure::route('/create'),
            'edit' => Pages\EditUnitOfMeasure::route('/{record}/edit'),
        ];
    }
}
