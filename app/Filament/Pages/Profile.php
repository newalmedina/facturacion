<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\{TextInput, FileUpload, Toggle, DatePicker, Radio, Select, Section, Grid};
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Collection;
use App\Models\State;
use App\Models\City;
use App\Models\Country;

class Profile extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = null; // Oculta el icono del menú
    protected static bool $shouldRegisterNavigation = false; // No registrar en el menú lateral
    protected static string $view = 'filament.pages.profile';
    protected static ?string $title = 'Perfil';

    public ?array $data = [];

    public function mount()
    {
        $user = Auth::user();
        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'image' => $user->image,
            'active' => $user->active,
            'identification' => $user->identification,
            'gender' => $user->gender,
            'phone' => $user->phone,
            'birth_date' => $user->birth_date,
            'country_id' => $user->country_id,
            'state_id' => $user->state_id,
            'city_id' => $user->city_id,
            'postal_code' => $user->postal_code,
            'address' => $user->address,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(12)
                ->schema([
                    Section::make()
                        ->columnSpan(3)
                        ->schema([
                            FileUpload::make('image')
                                ->image()
                                ->directory('users')
                                ->visibility('public')
                                ->label('Imagen'),
                        ]),
                    Grid::make(9)
                        ->columnSpan(9)
                        ->schema([
                            Section::make('Información de acceso')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('name')
                                        ->required()
                                        ->label("Nombre")
                                        ->maxLength(255),
                                    TextInput::make('email')
                                        ->email()
                                        ->label("Email")
                                        ->disabled(),
                                    TextInput::make('password')
                                        ->label("Contraseña")
                                        ->nullable() // Permite que el campo sea opcional
                                        ->dehydrated(fn($state) => filled($state))
                                        ->helperText(new HtmlString('<span style="color:#00B5D8">El campo solo se actualizará si ingresas un nuevo valor.</span>')),
                                 
                                ]),
                            Section::make('Información personal')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('identification'),
                                    Radio::make('gender')
                                        ->label('Género')
                                        ->options([
                                            'masc' => 'Masculino',
                                            'fem' => 'Femenino',
                                        ])
                                        ->inline()
                                        ->inlineLabel(false),
                                    TextInput::make('phone')
                                        ->maxLength(255)
                                        ->label('Teléfono'),
                                    DatePicker::make('birth_date')
                                        ->label('Fecha nacimiento'),
                                    Select::make('country_id')
                                        ->options(Country::query()->get()->where("is_active",1)->pluck('name', 'id'))
                                        ->searchable()
                                        ->label("País")
                                        ->preload()
                                        ->live()
                                        ->afterStateUpdated(function (Set $set) {
                                            $set('state_id', null);
                                            $set('city_id', null);
                                        }),
                                    
                                    Select::make('state_id')
                                        ->options(fn(Get $get) => State::where('country_id', $get('country_id'))->get()->pluck('name', 'id'))
                                        ->searchable()
                                        ->label("Estado")
                                        ->preload()
                                        ->live()
                                        ->afterStateUpdated(fn(Set $set) => $set('city_id', null)),
                                    
                                    Select::make('city_id')
                                        ->options(fn(Get $get) => City::where('state_id', $get('state_id'))->get()->pluck('name', 'id'))
                                        ->searchable()
                                        ->label("Ciudad")
                                        ->preload(),
                                    TextInput::make('postal_code')
                                        ->label("Código postal"),
                                    TextInput::make('address')
                                        ->label("Dirección")
                                        ->columnSpan(2),
                                ]),
                        ])
                ])
        ])->statePath('data');
    }

    public function save()
    {
        $user = Auth::user();
        $data = $this->form->getState();

        $user->update([
            'name' => $data['name'],
            'image' => $data['image'],
            'identification' => $data['identification'],
            'gender' => $data['gender'],
            'phone' => $data['phone'],
            'birth_date' => $data['birth_date'],
            'country_id' => $data['country_id'],
            'state_id' => $data['state_id'],
            'city_id' => $data['city_id'],
            'postal_code' => $data['postal_code'],
            'address' => $data['address'],
        ]);

        if (!empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        Notification::make()
            ->title('Perfil actualizado')
            ->success()
            ->send();
    }
}
