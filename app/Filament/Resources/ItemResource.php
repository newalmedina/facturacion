<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'fas-box';
    protected static ?string $navigationGroup = 'Tablas de sistemas';
    protected static ?int $navigationSort = 10;
    public static function getModelLabel(): string
    {
        return 'Item';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Items';
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
                                    ->directory('items')
                                    ->visibility('public')
                                    ->label('Imagen'),
                            ]),

                        Section::make('Información general')
                            ->columnSpan(9) // Ocupa 10 columnas de las 12 disponibles
                            ->schema([
                                // Campo Tipo
                                Forms\Components\Select::make('type')
                                    ->label("Tipo")
                                    ->required()
                                    ->options([
                                        'product' => 'Producto',
                                        'service' => 'Servicio',
                                    ])
                                    // ->afterStateUpdated(function ($state, $get, $set) {
                                    //     // Si 'type' es 'service', establece los campos a null
                                    //     if ($state === 'service') {
                                    //         // Actualiza los campos a null cuando el 'type' es 'service'
                                    //         $set('brand_id', null);
                                    //         $set('supplier_id', null);
                                    //         $set('unit_of_measure_id', null);
                                    //         $set('amount', null);
                                    //     }
                                    // })
                                    ->reactive(),


                                // Campo Nombre
                                Forms\Components\TextInput::make('name')
                                    ->label("Nombre")
                                    ->required()
                                    ->hidden(fn($get) => empty($get('type')))
                                    ->maxLength(255),

                                // Campo Descripción
                                Forms\Components\Textarea::make('description')
                                    ->label("Descripción")
                                    ->hidden(fn($get) => empty($get('type')))
                                    ->columnSpanFull(),

                                // Campo Activo
                                Forms\Components\Toggle::make('active')
                                    ->label("Activo")
                                    ->hidden(fn($get) => empty($get('type')))
                                    ->required(),

                                // Campo Categoría (Solo visible cuando 'type' es 'service')
                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name', function ($query) {
                                        $query->where('active', true);
                                    })
                                    ->searchable()
                                    ->hidden(fn($get) => empty($get('type')))
                                    ->label("Categoría")
                                    ->preload(),

                                // Campo Precio (Solo visible cuando 'type' es 'service')
                                Forms\Components\TextInput::make('price')
                                    ->label("Precio")
                                    ->numeric()
                                    ->hidden(fn($get) => empty($get('type')))
                                    ->prefix('€'),

                                // Campo IVA (Solo visible cuando 'type' es 'service')
                                Forms\Components\TextInput::make('taxes')
                                    ->label("IVA")
                                    ->prefix('%')
                                    ->hidden(fn($get) => empty($get('type')))
                                    ->numeric(),




                                // Campo Marca (Solo visible cuando 'type' es 'product')
                                Forms\Components\Select::make('brand_id')
                                    ->relationship('brand', 'name', function ($query) {
                                        $query->where('active', true);
                                    })
                                    ->searchable()
                                    ->label("Marca")
                                    ->preload()
                                    ->reactive()
                                    ->hidden(fn($get) => $get('type') === 'service' || empty($get('type'))), // Solo visible para 'product'

                                // Campo Suplidor (Solo visible cuando 'type' es 'product')
                                Forms\Components\Select::make('supplier_id')
                                    ->relationship('supplier', 'name', function ($query) {
                                        $query->where('active', true);
                                    })
                                    ->searchable()
                                    ->label("Suplidor")
                                    ->preload()
                                    ->reactive()
                                    ->hidden(fn($get) => $get('type') === 'service' || empty($get('type'))), // Solo visible para 'product'

                                // Campo Unidad de medida (Solo visible cuando 'type' es 'product')
                                Forms\Components\Select::make('unit_of_measure_id')
                                    ->relationship('unitOfMeasure', 'name', function ($query) {
                                        $query->where('active', true);
                                    })
                                    ->searchable()
                                    ->label("Unidad de medida")
                                    ->preload()
                                    ->reactive()
                                    ->hidden(fn($get) => $get('type') === 'service' || empty($get('type'))), // Solo visible para 'product'

                                // Campo Cantidad (Solo visible cuando 'type' es 'product')
                                Forms\Components\TextInput::make('amount')
                                    ->label("Cantidad")
                                    ->numeric()
                                    ->reactive()
                                    ->hidden(fn($get) => $get('type') === 'service' || empty($get('type'))), // Solo visible para 'product'
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
                    ->circular() // Hace la imagen circular
                    ->disk('public'), // Especifica el disco 'public'
                Tables\Columns\TextColumn::make('type')
                    ->label("Tipo"),
                Tables\Columns\TextColumn::make('name')
                    ->label("nombre")
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->label("Precio")
                    ->money()
                    ->searchable()
                    ->formatStateUsing(fn($state) => number_format($state, 2) . '€')
                    ->sortable(),

                Tables\Columns\TextColumn::make('taxes')
                    ->label("IVA %")
                    ->searchable()
                    ->numeric()
                    ->formatStateUsing(fn($state) => number_format($state, 2))
                    ->sortable(),
                Tables\Columns\TextColumn::make('taxes_amount')  // Utilizando el atributo taxes_amount
                    ->label("Impuestos")
                    ->searchable()
                    ->formatStateUsing(fn($state) => number_format($state, 2) . '€')  // Formateamos el valor con dos decimales
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_price')  // Utilizando el atributo total_price
                    ->label("Precio Total")
                    ->searchable()
                    ->formatStateUsing(fn($state) => number_format($state, 2) . '€')  // Formateamos el valor con dos decimales
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label("cantidad")
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable()
                    ->label("Categoría")
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->label("Activo")

                    ->boolean(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label("Marca")
                    // ->numeric()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('unitOfMeasure.name')
                    ->label("Unidad medida")
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->numeric()
                    ->label("Proveedor")
                    ->searchable()
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
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
                SelectFilter::make('type')
                    ->label("Tipo")
                    ->options([
                        'service' => 'Servicios',
                        'product' => 'Productos',
                    ]),
                SelectFilter::make('category_id')
                    ->relationship(name: 'category', titleAttribute: 'name')
                    ->searchable()
                    ->label("Categoría")
                    ->preload(),
                SelectFilter::make('brand_id')
                    ->relationship(name: 'brand', titleAttribute: 'name')
                    ->searchable()
                    ->label("Marca")
                    ->preload(),
                SelectFilter::make('supplier_id')
                    ->relationship(name: 'supplier', titleAttribute: 'name')
                    ->searchable()
                    ->label("Proveedor")
                    ->preload(),
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
