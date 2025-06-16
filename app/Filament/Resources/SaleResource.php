<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Mail\OrderDeletedMail;
use App\Mail\ReceiptMail;
use App\Models\Order;
use App\Models\Setting;
use App\Services\ReceiptService;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Mail;

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

    public static function table(Table $table): Table
    {
        return $table
            ->searchPlaceholder('Buscar código')
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
                Tables\Columns\TextColumn::make('customer.name')
                    ->numeric()
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
                /*Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),*/
            ])
            ->filters([
                //
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
                            ->title('Factura enviada por email')
                            ->success()
                            ->send();
                    }),

                // 2. Enviar por WhatsApp
                /*Tables\Actions\Action::make('sendWhatsapp')
                    ->label('')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success') // verde
                    ->tooltip('Enviar la factura por WhatsApp')
                    ->visible(fn($record) => $record->status === 'invoiced')
                    ->modalHeading('Enviar factura por WhatsApp')
                    ->form([
                        Forms\Components\TextInput::make('phone')
                            ->label('Teléfono (código país + número)')
                            ->tel()
                            ->required(),
                        Forms\Components\Textarea::make('message')
                            ->label('Mensaje')
                            ->default(fn($record) => "Hola, te envío la factura #{$record->id}")
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $phone   = preg_replace('/\D+/', '', $data['phone']);
                        $message = urlencode($data['message']);
                        Notification::make()
                            ->title('WhatsApp preparado')
                            ->body("Haz clic para abrir WhatsApp.")
                            ->action(
                                url: "https://wa.me/{$phone}?text={$message}",
                                label: 'Abrir WhatsApp'
                            )
                            ->send();
                    }),*/

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
                    // Tables\Actions\DeleteBulkAction::make(),
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
