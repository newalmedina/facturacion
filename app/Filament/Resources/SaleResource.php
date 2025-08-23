<?php

namespace App\Filament\Resources;

use App\Exports\OtherExpenseExport;
use App\Exports\SaleDetailExport;
use App\Exports\SaleExport;
use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Mail\OrderDeletedMail;
use App\Mail\ReceiptMail;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OtherExpenseItem;
use App\Models\Setting;
use App\Models\User;
use App\Services\ReceiptService;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class SaleResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?int $navigationSort = 30;
    // protected static ?string $navigationLabel = 'Ciudadedsadss';
    public static function getModelLabel(): string
    {
        return 'Venta';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Ventas';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('type')
                    ->required(),
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {

        return parent::getEloquentQuery()->sales();
    }


    public static function table(Table $table): Table
    {
        return $table
            ->searchPlaceholder('Buscar código, cliente, vendedor,observaciones')
            ->query(fn() => \App\Models\Order::query()->withCalculatedTotals()->sales())

            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->label("Código")
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->label("Fecha")
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('d-m-Y');
                    })

                    ->sortable(),
                //Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->numeric()
                    ->label("Vendedor")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->numeric()
                    ->label("Cliente")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('products')
                    ->label('Productos')
                    ->limit(50) // Limita los caracteres visibles (opcional)
                    ->toggleable() // Permite ocultar/mostrar la columna desde el UI
                    ->sortable(false) // No se puede ordenar si es un atributo calculado directamente
                    ->searchable(false), // Solo si quieres permitir búsqueda en este campo
                Tables\Columns\TextColumn::make('observations')
                    ->searchable()
                    ->toggleable()
                    ->label("Observaciones")
                    ->sortable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('EUR') // Puedes usar 'USD', 'MXN', etc., según tu caso
                    ->sortable(),

                Tables\Columns\TextColumn::make('impuestos')
                    ->label('Impuestos')
                    ->money('EUR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('EUR')
                    ->sortable(),

                //Tables\Columns\TextColumn::make('status'),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'invoiced' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'invoiced' => 'Facturado',
                        default => ucfirst($state),
                    }),
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
                        Select::make('assigned_user_ids')
                            ->label('Vendedores')
                            ->options(
                                User::all()->pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->multiple()
                            ->native(false)
                            ->placeholder('Selecciona vendedor (s)'),
                        Select::make('customer_ids')
                            ->label('Clientes')
                            ->options(
                                Customer::active()->pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->multiple()
                            ->native(false)
                            ->placeholder('Selecciona cliente(s)'),
                        TextInput::make('observations')->label("Observaciones"),
                        Select::make('status')
                            ->label('Estado')
                            ->options([
                                'pending' => 'Pendiente',
                                'invoiced' => 'Facturado',
                            ])
                            ->native(false)
                            ->placeholder('Selecciona estado'),
                        /* Select::make('items')
                            ->label('Items')
                            ->multiple()
                            ->searchable()
                            ->options(OtherExpenseItem::all()->pluck('name', 'name')) // Aquí obtienes las opciones del modelo
                            ->preload(),*/
                    ])
                    ->indicateUsing(function (array $data): array {
                        $filter = [];

                        // Si 'date_from' y 'date_until' están llenos, aplicamos el filtro de fecha
                        if (isset($data['date_from'])) {
                            $filter['date_from'] = "Desde " .  Carbon::parse($data['date_from'])->format("d-m-Y");  // Fecha inicio
                        }
                        if (isset($data['date_until'])) {
                            $filter['date_until'] = "Hasta " .  Carbon::parse($data['date_until'])->format("d-m-Y");   // Fecha fin
                        }
                        if (isset($data['observations'])) {
                            $filter['observations'] = $data['observations'];  // Fecha inicio
                        }
                        if (isset($data['status'])) {
                            $filter['status'] = $data['status'] == "invoiced" ? "Facturado" : "Pendiente";  // Fecha inicio
                        }

                        if (!empty($data['customer_ids']) && is_array($data['customer_ids'])) {
                            $names = Customer::whereIn('id', $data['customer_ids'])->pluck('name')->toArray();
                            $filter["customer_ids"] = 'Clientes: ' . implode(', ', $names);
                        }
                        if (!empty($data['assigned_user_ids']) && is_array($data['assigned_user_ids'])) {
                            $names = Customer::whereIn('id', $data['assigned_user_ids'])->pluck('name')->toArray();
                            $filter["assigned_user_ids"] = 'Vendedores: ' . implode(', ', $names);
                        }
                        return $filter;
                    })
                    ->query(function ($query, array $data) {
                        // Aplica el filtro en la consulta
                        if (!empty($data['customer_ids']) && is_array($data['customer_ids'])) {
                            $names = Customer::whereIn('id', $data['customer_ids'])->pluck('name')->toArray();
                        }
                        if (!empty($data['assigned_user_ids']) && is_array($data['assigned_user_ids'])) {
                            $names = User::whereIn('id', $data['assigned_user_ids'])->pluck('name')->toArray();
                        }
                        if (isset($data['date_from']) && !empty($data['date_from'])) {
                            $query->where('date', '>=', $data['date_from']);
                        }
                        if (isset($data['date_until']) && !empty($data['date_until'])) {
                            $query->where('date', '<=', $data['date_until']);
                        }
                        if (isset($data['observations']) && !empty($data['observations'])) {
                            $query->where('observations', 'like', '%' . $data['observations'] . '%');
                        }
                        if (isset($data['status']) && !empty($data['status'])) {
                            $query->where('status',  $data['status']);
                        }
                        if (isset($data['items'])) {
                            if (count($data['items']) > 0) {
                                $query->whereHas('details.item', function ($query) use ($data) {
                                    $query->whereIn('name', $data['items']);
                                });
                            }
                        }
                        if (!empty($data['customer_ids']) && is_array($data['customer_ids'])) {
                            $query->whereIn('customer_id', $data['customer_ids']);
                        }
                        if (!empty($data['assigned_user_ids']) && is_array($data['assigned_user_ids'])) {
                            $query->whereIn('assigned_user_id', $data['assigned_user_ids']);
                        }


                        return $query;
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('')->tooltip('Editar'),
                // 1. Enviar factura por email (solo si está facturado)
                // 1. Enviar factura por e-mail
                Tables\Actions\Action::make('generateInvoice')
                    ->label('')
                    ->icon('heroicon-o-document-text')  // Ícono de recibo/factura
                    ->color('secondary')            // Azul, por ejemplo
                    ->tooltip('Generar recibo')
                    ->visible(fn($record) => $record->status === 'invoiced')
                    ->action(function ($record) {
                        // Aquí va la lógica para generar la factura
                        // $record->generateInvoice();

                        $receiptService = new ReceiptService();
                        $pdf = $receiptService->generate($record);

                        return  response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, $record->code . '.pdf');
                    }),
                Tables\Actions\Action::make('sendInvoiceEmail')
                    ->label('')
                    ->icon('heroicon-o-envelope')
                    ->color('primary') // azul
                    ->tooltip('Enviar la factura por correo electrónico')
                    ->visible(fn($record) => $record->status === 'invoiced')
                    ->modalHeading('Enviar factura por correo electrónico')
                    ->form([
                        Forms\Components\Select::make('email_option')
                            ->label('Selecciona correo')
                            ->options([
                                'same' => 'Mismo correo del cliente',
                                'other' => 'Otro correo',
                            ])
                            ->afterStateUpdated(function (callable $get, $set, $record) {
                                $set('email', null);
                                if ($get('email_option') === 'same' && $record?->customer?->email) {
                                    $set('email', $record->customer->email);
                                }
                            })
                            ->default('same')
                            ->reactive(),  // permite reaccionar a cambios

                        Forms\Components\TextInput::make('email')
                            ->label('Correo electrónico')
                            ->email()
                            ->required()
                            ->disabled(fn(callable $get) => $get('email_option') === 'same')
                            ->afterStateHydrated(function ($state, callable $get, $set, $record) {
                                // Al cargar el formulario por primera vez
                                if ($get('email_option') === 'same' && $record?->customer?->email) {
                                    $set('email', $record->customer->email);
                                }
                            })

                            ->dehydrated(),


                    ])
                    ->action(function ($record, array $data) {
                        // Mail::to($data['email'])->send(new InvoiceMail($record));
                        $receiptService = new ReceiptService();
                        $pdf = $receiptService->generate($record);

                        // Enviar correo a la dirección del cliente
                        Mail::to($data['email'])
                            ->send(new ReceiptMail($pdf, $record));

                        /*  Mail::to($record->customer->email)
                            ->send(new ReceiptMail($pdf, $record));*/


                        Notification::make()
                            ->title('Recibo enviado por email')
                            ->success()
                            ->send();
                    }),



                // 3. Facturar
                Tables\Actions\Action::make('invoice')
                    ->label('')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('warning') // amarillo
                    ->tooltip('Generar factura')
                    ->visible(fn($record) => $record->status !== 'invoiced')
                    ->requiresConfirmation()
                    ->modalHeading('Confirmar facturación')
                    ->action(function ($record) {
                        $record->status = "invoiced";
                        $record->save();

                        Notification::make()
                            ->title('Factura generada')
                            ->success()
                            ->send();
                    }),

                // 4. Revertir facturación
                Tables\Actions\Action::make('revertInvoice')
                    ->label('')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('danger') // rojo
                    ->tooltip('Revertir la facturación')
                    ->visible(fn($record) => $record->status === 'invoiced')
                    ->requiresConfirmation()
                    ->modalHeading('Confirmar reversión de facturación')
                    ->action(function ($record) {

                        $record->status = "pending";
                        $record->save();
                        Notification::make()
                            ->title('Facturación revertida')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->tooltip('Eliminar')
                    ->visible(fn($record) => !$record->disabled_sales)
                    ->after(function ($record) {
                        // Enviar el correo
                        $settings = Setting::first();
                        if ($settings && $settings->general) {
                            $generalSettings = $settings->general;

                            if (!empty($generalSettings->email)) {
                                $email = str_replace('"', '', $generalSettings->email);

                                Mail::to($email)->send(new OrderDeletedMail($record));
                            }
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('export')->label('Exportar ' . self::getPluralModelLabel())->icon('heroicon-m-arrow-down-tray')
                        ->action(function ($records) {

                            $modelLabel = self::getPluralModelLabel();
                            // Puedes agregar la fecha o cualquier otro dato para personalizar el nombre
                            $fileName = $modelLabel . '-' . now()->format('d-m-Y') . '.xlsx'; // Ejemplo: "Marcas-2025-03-14.xlsx"

                            // Preparamos la consulta para exportar
                            $query = \App\Models\Order::whereIn('id', $records->pluck('id'));

                            // Llamamos al método Excel::download() y pasamos el nombre dinámico del archivo
                            return Excel::download(new SaleExport($query), $fileName);
                        }),
                    BulkAction::make('exportDetail')->label('Exportar detalle ' . self::getPluralModelLabel())->icon('heroicon-m-arrow-down-tray')
                        ->action(function ($records) {

                            $modelLabel = self::getPluralModelLabel();
                            // Puedes agregar la fecha o cualquier otro dato para personalizar el nombre
                            $fileName = $modelLabel .  ' detalle -' . now()->format('d-m-Y') . '.xlsx'; // Ejemplo: "Marcas-2025-03-14.xlsx"

                            // Preparamos la consulta para exportar
                            $query = \App\Models\OrderDetail::whereIn('order_id', $records->pluck('id'));

                            // Llamamos al método Excel::download() y pasamos el nombre dinámico del archivo
                            return Excel::download(new SaleDetailExport($query), $fileName);
                        }),
                ]),
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
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
