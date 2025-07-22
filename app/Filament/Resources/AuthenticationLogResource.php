<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthenticationLogResource\Pages;
use App\Filament\Resources\AuthenticationLogResource\RelationManagers;
use App\Models\AuthenticationLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class AuthenticationLogResource extends Resource
{
    protected static ?string $model = AuthenticationLog::class;
    protected static ?string $navigationLabel = 'Log autenticación';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Administración usuarios';
    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return 'Logs autenticación';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Logs autenticación';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('authenticatable.name')->label('Usuario'),
                TextColumn::make('ip_address')->label('Dirección IP'),
                TextColumn::make('user_agent')->label('Agente de usuario')->limit(50),
                TextColumn::make('login_at')
                    ->label('Inicio de sesión')
                    ->dateTime('d-m-Y H:i'),
                Tables\Columns\IconColumn::make('login_successful')
                    ->boolean()
                    ->label('¿Inicio exitoso?'),
                TextColumn::make('logout_at')
                    ->label('Cierre de sesión')
                    ->dateTime('d-m-Y H:i'),
            ])

            ->filters([
                SelectFilter::make('login_successful')
                    ->label("Estado login")
                    ->options([
                        '1' => 'Inicio sesión exitoso',
                        '0' => 'Inicio sesión fallido',
                    ]),

                // Filtro por rango de fechas de inicio de sesión
                Filter::make('login_at_range')
                    ->label('Rango de inicio de sesión')
                    ->form([
                        DatePicker::make('login_start')
                            ->label('Fecha inicial login'),
                        DatePicker::make('login_end')
                            ->label('Fecha final login'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['login_start'], fn($q) => $q->whereDate('login_at', '>=', $data['login_start']))
                            ->when($data['login_end'], fn($q) => $q->whereDate('login_at', '<=', $data['login_end']));
                    }),

                // Filtro por rango de fechas de logout
                Filter::make('logout_at_range')
                    ->label('Rango de cierre de sesión')
                    ->form([
                        DatePicker::make('logout_start')
                            ->label('Fecha inicial logout'),
                        DatePicker::make('logout_end')
                            ->label('Fecha final logout'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['logout_start'], fn($q) => $q->whereDate('logout_at', '>=', $data['logout_start']))
                            ->when($data['logout_end'], fn($q) => $q->whereDate('logout_at', '<=', $data['logout_end']));
                    }),
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAuthenticationLogs::route('/'),
            // 'create' => Pages\CreateAuthenticationLog::route('/create'),
            // 'edit' => Pages\EditAuthenticationLog::route('/{record}/edit'),
        ];
    }
}
