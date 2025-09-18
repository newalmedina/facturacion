<?php

namespace App\Filament\Resources;

use App\Exports\ItemExport;
use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Maatwebsite\Excel\Facades\Excel;

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
                            ->columnSpan([
                                'default' => 12, // móvil
                                'md' => 3,       // escritorio
                            ]) // Ocupa 2 columnas de las 12 disponibles
                            ->schema([
                                // FileUpload::make('image')
                                //     ->image()
                                //     ->directory('items')
                                //     ->visibility('public')
                                //     ->label('Imagen'),
                                FileUpload::make('image')
                                    ->image()
                                    ->directory('items')
                                    ->visibility('public')
                                    ->label('Imagen')
                                    ->helperText('Resolución recomendada: 1000 × 667 píxeles')

                                    ->imageEditor()                        // habilita editor
                                    ->imageResizeMode('cover')              // recorta para llenar el tamaño
                                    ->imageCropAspectRatio('3:2')          // relación 1000x667 ≈ 3:2
                                    ->imageResizeTargetWidth(1000)         // ancho final
                                    ->imageResizeTargetHeight(667),      // alto final
                            ]),

                        Section::make('Información general')
                            ->columnSpan([
                                'default' => 12, // móvil
                                'md' => 9,       // escritorio
                            ]) // Ocupa 10 columnas de las 12 disponibles
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
                                // Campo Marca (Solo visible cuando 'type' es 'product')

                                // Forms\Components\Grid::make(2)
                                //     ->schema([
                                //         Forms\Components\TextInput::make('time_formatted')
                                //             ->label('Duración (HH:MM)')
                                //             ->placeholder('Ej. 01:30')
                                //             ->mask('99:99')
                                //             ->helperText('Introduce el tiempo en horas:minutos')
                                //             // ->dehydrated(false) // ⚠️ Muy importante para que no intente guardar en la BD
                                //             // ->required(fn($get) => $get('type') === 'service')
                                //             ->hidden(fn($get) => $get('type') !== 'service')
                                //             ->afterStateHydrated(function ($state, callable $set, $get) {
                                //                 $minutes = $get('time');
                                //                 if ($minutes !== null) {
                                //                     $hours = floor($minutes / 60);
                                //                     $mins = $minutes % 60;
                                //                     $set('time_formatted', sprintf('%02d:%02d', $hours, $mins));
                                //                 }
                                //             })
                                //             ->afterStateUpdated(function ($state, callable $set) {
                                //                 if (preg_match('/^(\d{1,2}):(\d{2})$/', $state, $matches)) {
                                //                     $hours = (int) $matches[1];
                                //                     $minutes = (int) $matches[2];
                                //                     $set('time', $hours * 60 + $minutes);
                                //                 } else {
                                //                     $set('time', null);
                                //                 }
                                //             }),

                                //         Forms\Components\TextInput::make('time')
                                //             ->label('Tiempo en minutos')
                                //             ->numeric()
                                //             ->hidden()
                                //             // ->dehydrated()
                                //             ->disabled(),




                                //     ])
                                //     ->visible(fn($get) => $get('type') === 'service'),

                                // ->required(),
                                // Campo Descripción
                                Forms\Components\Textarea::make('description')
                                    ->label("Descripción")
                                    ->hidden(fn($get) => empty($get('type')))
                                    ->columnSpanFull(),

                                Forms\Components\Toggle::make('active')
                                    ->inline(false)
                                    ->label("¿Activo?")
                                    ->hidden(fn($get) => empty($get('type')))
                                    ->required(),
                                // Campo Activo
                                Forms\Components\Toggle::make('show_booking')
                                    ->inline(false)
                                    ->label("Mostrar en reservas")
                                    ->hidden(fn($get) => empty($get('type')))
                                    ->required(),

                                Forms\Components\Toggle::make('show_booking_others')
                                    ->inline(false)
                                    ->label("Mostrar en reservas de otros")
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
                                    ->prefix('€')
                                    ->reactive()
                                    //->debounce(500)
                                    ->debounce(750)
                                    ->afterStateUpdated(fn($state, $get, $set) => self::updateCalculatedFields($get, $set)),



                                // Campo IVA (Solo visible cuando 'type' es 'service')
                                /* Forms\Components\TextInput::make('taxes')
                                    ->label("IVA")
                                    ->prefix('%')
                                    ->hidden(fn($get) => empty($get('type')))
                                    ->numeric()
                                    ->reactive()
                                    //->debounce(500)
                                    ->debounce(750)
                                    ->afterStateUpdated(fn($state, $get, $set) => self::updateCalculatedFields($get, $set)),*/

                                // 🔹 Campo calculado: Monto de impuestos
                                // 🔹 Campo calculado: Monto de impuestos
                                /*  Forms\Components\TextInput::make('taxes_amount')
                                    ->label("Monto de Impuestos")
                                    ->disabled()
                                    ->hidden(fn($get) => empty($get('type')))
                                    ->numeric()
                                    //->debounce(500)
                                    ->debounce(750)
                                    ->default(fn($get) => round(($get('price') * $get('taxes')) / 100, 2))
                                    ->prefix('€')
                                    ->afterStateHydrated(function ($get, $set) {
                                        if ($get('price') && $get('taxes')) {
                                            $set('taxes_amount', round(($get('price') * $get('taxes')) / 100, 2));
                                        }
                                    }),*/

                                // 🔹 Campo calculado: Precio total
                                /*Forms\Components\TextInput::make('total_price')
                                    ->label("Precio Total")
                                    ->disabled()
                                    ->hidden(fn($get) => empty($get('type')))
                                    ->numeric()
                                    ->default(fn($get) => round($get('price') + (($get('price') * $get('taxes')) / 100), 2))
                                    ->prefix('€')
                                    ->afterStateHydrated(function ($get, $set) {
                                        if ($get('price') && $get('taxes')) {
                                            $set('total_price', round($get('price') + (($get('price') * $get('taxes')) / 100), 2));
                                        }
                                    }),


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
                                    ->hidden(fn($get) => $get('type') === 'service' || empty($get('type'))), // Solo visible para 'product'*/

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
                // Tables\Columns\TextColumn::make('time')
                //     ->label('Tiempo')
                //     ->formatStateUsing(
                //         fn($state) => ($state !== null && $state > 0)
                //             ? sprintf('%02d:%02d h', floor($state / 60), $state % 60)
                //             : ''
                //     ),


                Tables\Columns\TextColumn::make('price')
                    ->label("Precio")
                    ->money()
                    ->searchable()
                    ->formatStateUsing(fn($state) => number_format($state, 2) . '€')
                    ->sortable(),

                /*Tables\Columns\TextColumn::make('taxes')
                    ->label("IVA %")
                    ->searchable()
                    ->numeric()
                    ->formatStateUsing(fn($state) => number_format($state, 2))
                    ->sortable(),*/
                /*Tables\Columns\TextColumn::make('taxes_amount')  // Utilizando el atributo taxes_amount
                    ->label("Impuestos")
                    // ->searchable()
                    ->formatStateUsing(fn($state) => number_format($state, 2) . '€')  // Formateamos el valor con dos decimales
                    ->sortable(),*/

                Tables\Columns\TextColumn::make('total_price')  // Utilizando el atributo total_price
                    ->label("Precio Total")
                    // ->searchable()
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
                    ->label("¿Activo?")

                    ->boolean(),
                Tables\Columns\IconColumn::make('show_booking')
                    ->label("Mostrar en reservas")
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->boolean(),
                Tables\Columns\IconColumn::make('show_booking_others')
                    ->label("Mostrar en reservas de otros")
                    ->toggleable(isToggledHiddenByDefault: true)
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
                SelectFilter::make('active')
                    ->label("¿Activo?")
                    ->options([
                        '1' => 'Activo',
                        '0' => 'No activo',
                    ]),
                SelectFilter::make('show_booking')
                    ->label("Mostrar en reservas")
                    ->options([
                        '1' => 'Mostrar',
                        '0' => 'No mostrar',
                    ]),
                SelectFilter::make('show_booking_others')
                    ->label("Mostrar en reservas de otros")
                    ->options([
                        '1' => 'Mostrar',
                        '0' => 'No mostrar',
                    ]),
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
                Tables\Actions\EditAction::make()->label('')->tooltip('Editar'),
                Tables\Actions\DeleteAction::make()->label('')->tooltip('Eliminar')
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
                        $query = \App\Models\Item::whereIn('id', $records->pluck('id'));

                        // Llamamos al método Excel::download() y pasamos el nombre dinámico del archivo
                        return Excel::download(new ItemExport($query), $fileName);
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Función para actualizar los campos calculados en tiempo real
     */
    public static function updateCalculatedFields($get, $set)
    {
        $price = round((float) $get('price'), 2);
        $taxes = round((float) $get('taxes'), 2);

        $taxAmount = round(($price * $taxes) / 100, 2);
        $totalPrice = round($price + $taxAmount, 2);

        $set('taxes_amount', $taxAmount);
        $set('total_price', $totalPrice);
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
