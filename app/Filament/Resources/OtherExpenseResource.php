<?php

namespace App\Filament\Resources;

use App\Exports\ItemExport;
use App\Exports\OtherExpenseExport;
use App\Filament\Resources\OtherExpenseResource\Pages;
use App\Filament\Resources\OtherExpenseResource\RelationManagers;
use App\Models\OtherExpense;
use App\Models\OtherExpenseItem;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Filament\Tables\Filters\DateFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\HtmlString;
use Maatwebsite\Excel\Facades\Excel;

class OtherExpenseResource extends Resource
{
    protected static ?string $model = OtherExpense::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?int $navigationSort = 31;
    // protected static ?string $navigationLabel = 'Ciudadedsadss';
    public static function getModelLabel(): string
    {
        return 'Otros gasto';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Otros gastos';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Fila 1: Total alineado a la derecha
                Grid::make(12)
                    ->schema([
                        Placeholder::make('') // columna vacía
                            ->columnSpan(10),
                        Placeholder::make('')
                            ->label(null)
                            ->content(function (Get $get): HtmlString {
                                $total = collect($get('details'))
                                    ->sum(fn($item) => floatval($item['price'] ?? 0));

                                $formattedTotal = number_format($total, 2) . '€';

                                /*return new HtmlString(
                                    '<div class="w-full flex justify-end text-center">
                                        <div style="background-color: #28a745; color: white; font-weight: bold; font-size: 1.25rem; padding: 0.75rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">                                 
                                            <p class="mb-2">Total:</p>    
                                            <span style="font-weight: bold; font-size: 1.5rem;" >' . $formattedTotal . '€</span>                                  
                                        </div>
                                    </div>'
                                );*/
                                return new HtmlString(
                                    '<div class="w-full flex justify-end text-center">
                                        <div class="bg-green-600 text-green font-bold text-xl px-6 py-3 rounded-lg shadow">
                                         <p class="mb-2">Total:</p>        
                                        <span style=" font-size: 1.5rem;">' . $formattedTotal . '</span>
                                        </div>
                                    </div>'
                                );
                            })
                            ->columnSpanFull()
                    ]),

                // Fila 2: Fecha y Descripción
                Grid::make(12)
                    ->schema([
                        DatePicker::make('date')
                            ->label("Fecha")
                            ->required()
                            ->columnSpan(2),

                        TextInput::make('description')
                            ->label("Descripción")
                            ->maxLength(255)
                            ->columnSpan(10),
                    ]),

                Actions::make([
                    Action::make('createOtherExpenseItem')
                        ->label('Agregar Item')
                        ->icon('heroicon-o-plus')
                        ->action(function (array $data): void {
                            OtherExpenseItem::create([
                                'name' => $data['name'],
                            ]);
                            Notification::make()
                                ->title('Ítem creado correctamente')
                                ->success()
                                ->send();
                        })
                        ->form([
                            TextInput::make('name')
                                ->label('Nombre del ítem')
                                ->unique(table: OtherExpenseItem::class)
                                ->required(),
                        ])
                        ->modalHeading('Nuevo Gasto Extra')
                        ->modalSubmitActionLabel('Guardar')
                        ->modalWidth('md'),
                ]),

                Repeater::make('details')
                    ->label("Detalles")
                    ->relationship('details')
                    ->live()
                    ->afterStateHydrated(function (Get $get, Set $set) {
                        $total = collect($get('details'))
                            ->sum(fn($item) => floatval($item['price'] ?? 0));
                        $set('total', number_format($total, 2, '.', ''));
                    })
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $total = collect($get('details'))
                            ->sum(fn($item) => floatval($item['price'] ?? 0));
                        $set('total', number_format($total, 2, '.', ''));
                    })
                    ->schema([
                        Grid::make(12)->schema([
                            Select::make('other_expense_item_id')
                                ->label("Items")
                                ->options(OtherExpenseItem::active()->pluck('name', 'id'))
                                ->searchable()
                                ->required()
                                ->columnSpan(10),

                            TextInput::make('price')
                                ->label("Precio")
                                ->numeric()
                                ->required()
                                ->prefix('€')->reactive()
                                ->debounce(500)
                                ->columnSpan(2),

                            TextInput::make('observations')
                                ->label("Observaciones")
                                ->maxLength(255)
                                ->columnSpan(12),
                        ]),
                    ])
                    ->minItems(1)
                    ->columnSpanFull(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                OtherExpense::query()
                    // Usamos 'withSum' para calcular la suma de los precios en los detalles relacionados
                    ->withSum('details', 'price')  // 'details' es la relación, 'price' es el campo a sumar
            )
            ->columns([

                Tables\Columns\TextColumn::make('date')
                    ->label("Fecha")
                    ->date()
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('d-m-Y');
                    })
                    //->toggleable(isToggledHiddenByDefault: true)
                    //->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')->sortable(),
                //->searchable(),


                // Columna para los nombres de los items, separados por coma
                Tables\Columns\TextColumn::make('itemnamestring')
                    ->label('Items')
                    ->formatStateUsing(function ($record) {
                        return $record->itemnamestring;  // Usamos el accesor "itemnamestring" que definimos
                    }),
                Tables\Columns\TextColumn::make('details_sum_price')  // El nombre generado por 'withSum' es 'details_sum_price'
                    ->label('Total')
                    ->formatStateUsing(function ($record) {
                        return number_format($record->details_sum_price, 2) . '€';
                    })
                    ->sortable(),  // Hacer que la columna 'total' sea ordenable

                /*Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->formatStateUsing(function ($record) {
                        return number_format($record->total, 2);  // Usamos el accesor "total" que definimos
                    }),*/
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label("Fecha creación")
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('d-m-Y h:i');
                    })
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),


            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('date_from')->label("Fecha inicio"),
                        DatePicker::make('date_until')->label("Fecha fin"),
                        TextInput::make('description')->label("Descripción"),
                        Select::make('items')
                            ->label('Items')
                            ->multiple()
                            ->searchable()
                            ->options(OtherExpenseItem::all()->pluck('name', 'name')) // Aquí obtienes las opciones del modelo
                            ->preload(),
                    ])
                    ->indicateUsing(function (array $data): array {
                        $filter = [];

                        // Si 'date_from' y 'date_until' están llenos, aplicamos el filtro de fecha
                        if (isset($data['date_from'])) {
                            $filter['date_from'] = Carbon::parse($data['date_from'])->format("d-m-Y");  // Fecha inicio
                        }
                        if (isset($data['date_until'])) {
                            $filter['date_until'] = Carbon::parse($data['date_until'])->format("d-m-Y");   // Fecha fin
                        }
                        if (isset($data['description'])) {
                            $filter['description'] = $data['description'];  // Fecha inicio
                        }
                        if (isset($data['items']) && !empty($data['items'])) {
                            $filter['items'] = implode(',', $data['items']);  // Fecha inicio
                        }

                        return $filter;
                    })
                    ->query(function ($query, array $data) {
                        // Aplica el filtro en la consulta
                        if (isset($data['date_from']) && !empty($data['date_from'])) {
                            $query->where('date', '>=', $data['date_from']);
                        }
                        if (isset($data['date_until']) && !empty($data['date_until'])) {
                            $query->where('date', '<=', $data['date_until']);
                        }
                        if (isset($data['description']) && !empty($data['description'])) {
                            $query->where('description', 'like', '%' . $data['description'] . '%');
                        }
                        if (isset($data['items'])) {
                            if (count($data['items']) > 0) {
                                $query->whereHas('details.item', function ($query) use ($data) {
                                    $query->whereIn('name', $data['items']);
                                });
                            }
                        }

                        return $query;
                    })
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label('')
            ])
            ->bulkActions([
                /*Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),*/
                BulkAction::make('export')->label('Exportar ' . self::getPluralModelLabel())->icon('heroicon-m-arrow-down-tray')
                    ->action(function ($records) {

                        $modelLabel = self::getPluralModelLabel();
                        // Puedes agregar la fecha o cualquier otro dato para personalizar el nombre
                        $fileName = $modelLabel . '-' . now()->format('d-m-Y') . '.xlsx'; // Ejemplo: "Marcas-2025-03-14.xlsx"

                        // Preparamos la consulta para exportar
                        $query = \App\Models\OtherExpenseDetail::whereIn('other_expense_id', $records->pluck('id'));

                        // Llamamos al método Excel::download() y pasamos el nombre dinámico del archivo
                        return Excel::download(new OtherExpenseExport($query), $fileName);
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
            'index' => Pages\ListOtherExpenses::route('/'),
            'create' => Pages\CreateOtherExpense::route('/create'),
            'edit' => Pages\EditOtherExpense::route('/{record}/edit'),
        ];
    }
}
