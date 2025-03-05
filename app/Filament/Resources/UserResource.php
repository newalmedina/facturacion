<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\City;
use App\Models\State;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Usuarios';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Administración usuarios';
    protected static ?int $navigationSort = 1;
    // protected static ?string $navigationLabel = 'Usuaios';
    public static function getModelLabel(): string
    {
        return 'Usuario';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Usuarios';
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

                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->label("Nombre")
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->label("Email")
                                            ->required()
                                            ->maxLength(255),
                                        // Forms\Components\DateTimePicker::make('email_verified_at'),

                                        Forms\Components\TextInput::make('password')
                                            ->label("Contraseña")
                                            // ->password()
                                            // ->hiddenOn('edit') // O puedes usar ->visibleOn('create') si quieres ocultarlo solo en edición
                                            ->required(fn(\Filament\Forms\Get $get) => !$get('id')) // Requiere el campo solo si es un registro nuevo
                                            ->dehydrated(fn($state) => filled($state)) // Solo actualiza si el campo tiene un valor
                                            ->helperText(fn(\Filament\Forms\Get $get) => $get('id')
                                                ? new HtmlString('<span style="color:#00B5D8">El campo solo se actualizará si ingresas un nuevo valor en el campo de contraseña.</span>')
                                                : null) // Muestra la leyenda en modo edición
                                        ,
                                        Forms\Components\Toggle::make('active')
                                            ->label("¿Activo?")
                                            ->inline(false)
                                            ->required(),


                                    ]),
                                Section::make('Información personal')
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('identification'),
                                        Forms\Components\Radio::make('gender')
                                            ->label('Género')
                                            ->options([
                                                'masc' => 'Masculino',
                                                'fem' => 'Femenino',
                                            ])
                                            ->inline()
                                            ->inlineLabel(false),
                                        Forms\Components\Select::make('country_id')
                                            ->relationship('country', 'name', function ($query) {
                                                $query->where('is_active', true);  // Filtro para que solo se muestren países activos
                                            })
                                            ->searchable()
                                            ->label("País")
                                            ->preload()
                                            ->live()
                                            ->afterStateUpdated(function (Set $set) {
                                                $set('state_id', null);
                                                $set('city_id', null);
                                            }),
                                        Forms\Components\Select::make('state_id')
                                            ->options(fn(Get $get): Collection => State::query()
                                                ->where('country_id', $get('country_id'))
                                                ->pluck('name', 'id'))
                                            ->searchable()
                                            ->label("Estado")
                                            ->preload()
                                            ->live()
                                            ->afterStateUpdated(fn(Set $set) => $set('city_id', null)),
                                        Forms\Components\Select::make('city_id')
                                            ->options(fn(Get $get): Collection => City::query()
                                                ->where('state_id', $get('state_id'))
                                                ->pluck('name', 'id'))
                                            ->searchable()
                                            ->label("Ciudad")
                                            ->preload(),
                                        Forms\Components\TextInput::make('postal_code')
                                            ->label("Código postal"),
                                        Forms\Components\TextInput::make('address')
                                            ->label("Dirección")
                                            ->columnSpan(2),

                                    ])->visible(fn($get) => $get('id')) // Solo visible al editar (cuando 'id' está presente)

                            ])
                    ])
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
                Tables\Columns\TextColumn::make('email')
                    ->label("Email")
                    ->searchable(),
                Tables\Columns\TextColumn::make('identification')
                    ->sortable()
                    ->label("Identificacion")
                    ->searchable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->sortable()
                    ->label("País")
                    ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean()
                    ->label("¿Activo?"),

                Tables\Columns\TextColumn::make('state.name')
                    ->sortable()
                    ->label("Estado")
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city.name')
                    ->sortable()
                    ->label("Ciudad")
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('email_verified_at')
                //     ->dateTime()
                //     //->label("Fecha verificación")
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label("Fecha creación")
                    ->dateTime()
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('d-m-Y h:i');
                    })
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->toggleable(isToggledHiddenByDefault: true)
                //     ->sortable(),
            ])
            ->filters([
                SelectFilter::make('active')
                    ->label("¿Activo?")
                    ->options([
                        '1' => 'Activo',
                        '0' => 'No activo',
                    ]),
                SelectFilter::make('country_id')
                    ->relationship(name: 'country', titleAttribute: 'name')
                    ->searchable()
                    ->label("País")
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(''),
                /*Tables\Actions\DeleteAction::make()->label('')->successNotificationTitle('Registro eliminado correctamente')
                    ->modalHeading('Eliminar registro')
                    ->modalDescription('¿Estás seguro de que deseas eliminar este registro?')
                    ->modalSubmitActionLabel('Si, eliminar')
                    ->modalCancelActionLabel('Cancelar') */
                Tables\Actions\DeleteAction::make()->label('')
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
