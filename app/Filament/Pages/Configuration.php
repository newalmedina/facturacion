<?php

namespace App\Filament\Pages;

use App\Mail\TestMail;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\{Actions, ColorPicker, TextInput, FileUpload, Toggle, DatePicker, Radio, Select, Section, Grid, Tabs};
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Tabs\Tab;
use Illuminate\Support\Facades\Mail;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Illuminate\Support\Facades\Artisan;

class Configuration extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationLabel = 'Configuración del sitio';
    protected static bool $shouldRegisterNavigation = true;

    protected static string $view = 'filament.pages.configuration';

    protected static ?string $navigationGroup = 'Configuraciones';

    protected static ?int $navigationSort = 82;
    public static function getNavigationLabel(): string
    {
        return 'Información del sitio';
    }
    public function getTitle(): string
    {
        return 'Información del sitio';
    }

    public ?array $data = [];

    public function mount()
    {
        $user = Auth::user();
        $center = $user->center; // Relación con el centro

        if ($center) {
            $this->form->fill([
                'name' => $center->name,
                'nif' => $center->nif,
                'email' => $center->email,
                'phone' => $center->phone,
                'address' => $center->address,
                'postal_code' => $center->postal_code,
                'country_id' => $center->country_id,
                'state_id' => $center->state_id,
                'city_id' => $center->city_id,
                'bank_name' => $center->bank_name,
                'bank_number' => $center->bank_number,
                'active' => $center->active,
                'image' => $center->image,
                'primary_color' => $center->primary_color,
                'primary_color_soft' => $center->primary_color_soft,
                'start_message' => $center->start_message,
                'end_message' => $center->end_message,
                'mail_enable_integration' => $center->mail_enable_integration,
                'enable_start_message' => $center->enable_start_message,
                'enable_end_message' => $center->enable_end_message,
            ]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(12)
                    ->schema([
                        Section::make('Imagen')
                            ->columnSpan([
                                'default' => 12, // móvil
                                'md' => 3,       // escritorio
                            ])
                            ->schema([
                                FileUpload::make('image')
                                    ->label('Imagen')
                                    ->image()
                                    ->disk('public')
                                    ->directory('centers')
                                    ->columnSpanFull(),
                            ]),


                        Tabs::make('Configuraciones')
                            ->columnSpan([
                                'default' => 12,
                                'md' => 9,
                            ])
                            ->tabs([
                                // --- Pestaña de Información General ---
                                Tab::make('Información general')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Actions::make([
                                                    FormAction::make('sendTestEmail')
                                                        ->label('Enviar correo de prueba')
                                                        ->icon('heroicon-o-envelope')
                                                        ->color('warning') // azul
                                                        ->action(function (): void {
                                                            $email = Auth()->user()->email;

                                                            Mail::to(Auth()->user()->email)->send(new TestMail(Auth()->user()));

                                                            Notification::make()
                                                                ->title('Correo de prueba enviado a ' . $email)
                                                                ->success()
                                                                ->send();
                                                        }),
                                                    FormAction::make('downloadOldBackup')
                                                        ->label('Descargar BBDD Antigua')
                                                        ->icon('heroicon-o-arrow-down')
                                                        ->extraAttributes([
                                                            'style' => 'background-color: #6B21A8; color: white;' // púrpura y texto blanco
                                                        ])
                                                        ->visible(fn() => auth()->check() && auth()->user()->email === 'el.solitions@gmail.com')
                                                        //  ->visible(false)

                                                        ->form([
                                                            TextInput::make('filename')
                                                                ->label('Nombre del archivo')
                                                                ->required()
                                                                ->placeholder('Ejemplo: laravel-backup/2025-07-22-19-54-30.zip'),
                                                        ])
                                                        ->action(function (array $data, $livewire) {
                                                            $filename = $data['filename'];

                                                            // Validar o sanitizar filename si quieres

                                                            // Ruta de descarga generada
                                                            $url = route('filament.backups.download', ['filepath' => $filename]);

                                                            // Redirigir usando Livewire
                                                            $livewire->redirect($url);
                                                        }),



                                                ])->visible(fn() => auth()->check() && auth()->user()?->super_admin == true),
                                                // Actions::make([
                                                //     FormAction::make('updateGmailToken')
                                                //         ->label('Actualizar Token Gmail')
                                                //         ->icon('heroicon-o-arrow-path')
                                                //         ->extraAttributes([
                                                //             'style' => 'background-color: #0ad3eeff; color: white;'
                                                //         ])
                                                //         ->action(function (): void {
                                                //             $output = Artisan::call('mail:generate-auth-url');
                                                //             $url = Artisan::output(); // <- obtiene la salida del comando

                                                //             // Extrae la URL de la salida (por si hay texto adicional)
                                                //             preg_match('/https?:\/\/[^\s]+/', $url, $matches);
                                                //             $authUrl = $matches[0] ?? null;

                                                //             if ($authUrl) {

                                                //                 dd($authUrl);
                                                //             } else {
                                                //                 Notification::make()
                                                //                     ->title('Error')
                                                //                     ->body('No se pudo generar la URL de autorización.')
                                                //                     ->danger()
                                                //                     ->send();
                                                //             }
                                                //         }),
                                                //     FormAction::make('updateReservas')
                                                //         ->label('Actualizar Reservas gmail')
                                                //         ->icon('heroicon-o-arrow-down')
                                                //         ->extraAttributes([
                                                //             'style' => 'background-color: #d10755ff; color: white;'
                                                //         ])
                                                //         ->visible(fn() => auth()->check() && auth()->user()?->can_show_general_resource == true)
                                                //         ->form([
                                                //             TextInput::make('minutos')
                                                //                 ->label('Número de minutos')
                                                //                 ->numeric()
                                                //                 ->minValue(1)
                                                //                 ->default(60)
                                                //                 ->required()
                                                //                 ->placeholder('Ejemplo: 60 (número de minutos)')

                                                //         ])
                                                //         ->action(function (array $data, $livewire) {
                                                //             $minutos = $data['minutos'];

                                                //             // Ejecutamos el comando con el parámetro y la opción --error=true
                                                //             \Artisan::call('actualizar_reservas', [
                                                //                 'tiempo' => $minutos,
                                                //                 '--error' => true,
                                                //             ]);

                                                //             // (Opcional) obtener la salida del comando
                                                //             $output = \Artisan::output();
                                                //             dd($output);
                                                //             // Puedes mostrar un mensaje en Filament
                                                //             // $livewire->notify('success', "Comando ejecutado correctamente. Salida: {$output}");
                                                //         }),
                                                //     FormAction::make('sendTestEmail')
                                                //         ->label('Enviar correo de prueba')
                                                //         ->icon('heroicon-o-envelope')
                                                //         ->color('warning')
                                                //         ->action(function (): void {
                                                //             $email = Auth()->user()->email;

                                                //             Mail::to($email)->send(new TestMail(Auth()->user()));

                                                //             Notification::make()
                                                //                 ->title('Correo de prueba enviado a ' . $email)
                                                //                 ->success()
                                                //                 ->send();
                                                //         }),

                                                //     FormAction::make('downloadOldBackup')
                                                //         ->label('Descargar BBDD Antigua')
                                                //         ->icon('heroicon-o-arrow-down')
                                                //         ->extraAttributes([
                                                //             'style' => 'background-color: #6B21A8; color: white;'
                                                //         ])
                                                //         ->visible(fn() => auth()->check() && auth()->user()?->can_show_general_resource == true)
                                                //         ->form([
                                                //             TextInput::make('filename')
                                                //                 ->label('Nombre del archivo')
                                                //                 ->required()
                                                //                 ->placeholder('Ejemplo: laravel-backup/2025-07-22-19-54-30.zip'),
                                                //         ])
                                                //         ->action(function (array $data, $livewire) {
                                                //             $filename = $data['filename'];
                                                //             $url = route('filament.backups.download', ['filepath' => $filename]);
                                                //             $livewire->redirect($url);
                                                //         }),
                                                // ]),

                                                TextInput::make('name')->label('Nombre')->required()->maxLength(255),
                                                TextInput::make('nif')->label('NIF/CIF')->required()->maxLength(255),
                                                TextInput::make('email')->label('Correo')->required()->email()->maxLength(255),
                                                TextInput::make('phone')->label('Teléfono')->tel()->maxLength(255),
                                                TextInput::make('address')->label('Dirección')->maxLength(255),
                                                TextInput::make('postal_code')->label('Código postal')->maxLength(255),

                                                Select::make('country_id')
                                                    ->label('País')
                                                    ->options(fn() => \App\Models\Country::pluck('name', 'id'))
                                                    ->searchable(),

                                                Select::make('state_id')
                                                    ->label('Estado')
                                                    ->options(fn(Get $get) => \App\Models\State::where('country_id', $get('country_id'))->pluck('name', 'id'))
                                                    ->searchable()
                                                    ->preload()
                                                    ->live()
                                                    ->afterStateUpdated(fn(Set $set) => $set('city_id', null)),

                                                Select::make('city_id')
                                                    ->label('Cidade')
                                                    ->options(fn(Get $get) => \App\Models\City::where('state_id', $get('state_id'))->pluck('name', 'id'))
                                                    ->searchable()
                                                    ->preload()
                                                    ->live(),

                                                TextInput::make('bank_name')->label('Entidad bancaria')->maxLength(255),
                                                TextInput::make('bank_number')->label('Número de cuenta')->maxLength(255),

                                                ColorPicker::make('primary_color')
                                                    ->label('Color Primario'),
                                                ColorPicker::make('primary_color_soft')
                                                    ->label('Color Primario suave'),
                                            ]),
                                    ]),

                                // --- Pestaña de Notificaciones ---
                                // Tab::make('Notificaciones')
                                //     ->schema([
                                //         Toggle::make('mail_enable_integration')
                                //             ->label('¿Habilitar integración de correo?')
                                //             ->columnSpan([
                                //                 'default' => 12, // móvil
                                //                 'md' => 6,       // escritorio
                                //             ])
                                //             ->inline(false)
                                //             ->default(false)
                                //             ->disabled(),
                                //         Toggle::make('enable_start_message')
                                //             ->label('¿Activar mensaje inicio alquiler?')
                                //             ->columnSpan([
                                //                 'default' => 12,
                                //                 'md' => 6,
                                //             ])
                                //             ->inline(false)
                                //             ->default(false)
                                //             ->reactive(), // <-- hace que otros campos reaccionen al cambio

                                //         // TinyEditor::make('start_message')
                                //         //     ->label("Mensaje antes alquiler")
                                //         //     ->fileAttachmentsDisk('public')
                                //         //     ->fileAttachmentsVisibility('public')
                                //         //     ->fileAttachmentsDirectory('uploads')
                                //         //     ->profile('default')
                                //         //     ->columnSpan('full')
                                //         //     ->required(fn($get) => $get('enable_start_message')) // obligatorio solo si toggle es true
                                //         //     ->reactive(), // <-- importante para que muestre validación en tiempo real

                                //         Toggle::make('enable_end_message')
                                //             ->label('¿Activar mensaje fin alquiler?')
                                //             ->columnSpan([
                                //                 'default' => 12,
                                //                 'md' => 6,
                                //             ])
                                //             ->inline(false)
                                //             ->default(false)
                                //             ->reactive(),

                                //         // TinyEditor::make('end_message')
                                //         //     ->label("Mensaje después alquiler")
                                //         //     ->fileAttachmentsDisk('public')
                                //         //     ->fileAttachmentsVisibility('public')
                                //         //     ->fileAttachmentsDirectory('uploads')
                                //         //     ->profile('full')
                                //         //     ->columnSpan('full')
                                //         //     ->required(fn($get) => $get('enable_end_message'))
                                //         //     ->reactive(),




                                //     ]),
                            ]),


                    ]),
            ])
            ->statePath('data');
    }

    public function save()
    {
        $user = Auth::user();
        $center = $user->center;

        if (!$center) {
            Notification::make()
                ->title('El usuario no tiene un centro asignado.')
                ->danger()
                ->send();
            return;
        }

        $data = $this->form->getState();
        // dd($data);
        $center->update($data);


        Notification::make()
            ->title('Centro actualizado correctamente')
            ->success()
            ->send();
    }
}
