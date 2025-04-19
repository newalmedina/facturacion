<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;
use App\Models\Brand;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Badge;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Exports\BrandExport;
use Filament\Tables\Actions\BulkAction;
use Maatwebsite\Excel\Facades\Excel;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Tablas de sistemas';
    protected static ?int $navigationSort = 12;
    public static function getModelLabel(): string
    {
        return 'Marca';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Marcas';
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
                                    ->directory('brands')
                                    ->visibility('public')
                                    ->label('Imagen'),
                                // Placeholder::make('created_at')
                                //     ->label('Fecha de Creación')
                                //     ->content(fn($get) => Carbon::parse($get('created_at'))->format('d-m-Y H:i')) // Formatea la fecha
                                //     ->hidden(fn($get) => !$get('id')), // Solo mostrar en edición

                            ]),

                        Section::make('Información general')
                            ->columnSpan(9) // Ocupa 10 columnas de las 12 disponibles
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label("Nombre")
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('description')
                                    ->label("Descripción")
                                    ->columnSpanFull(),
                                Forms\Components\Toggle::make('active')
                                    ->inline(false)
                                    ->label("¿Activo?")
                                    ->required(),
                            ]),
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
                Tables\Actions\DeleteAction::make()->label('')->visible(fn($record) =>  $record->canDelete)
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),


                ]),
                BulkAction::make('export')->label('Exportar ' . self::getPluralModelLabel())->icon('heroicon-m-arrow-down-tray')
                    ->action(function ($records) {

                        $modelLabel = self::getPluralModelLabel();
                        // Puedes agregar la fecha o cualquier otro dato para personalizar el nombre
                        $fileName = $modelLabel . '-' . now()->format('d-m-Y') . '.xlsx'; // Ejemplo: "Marcas-2025-03-14.xlsx"

                        // Preparamos la consulta para exportar
                        $query = \App\Models\Brand::whereIn('id', $records->pluck('id'));

                        // Llamamos al método Excel::download() y pasamos el nombre dinámico del archivo
                        return Excel::download(new BrandExport($query), $fileName);
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public function getActions(): array
    {
        return [
            Button::make('cancel')
                ->label('Cancelar')
                ->url(route('filament.resources.your-resource.index'))  // Asegúrate de cambiar esto
                ->color('secondary'),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrands::route('/'),
            /* 'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),*/
        ];
    }
}
