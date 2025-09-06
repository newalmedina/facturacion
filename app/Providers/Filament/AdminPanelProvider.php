<?php

namespace App\Providers\Filament;

use Tapp\FilamentAuthenticationLog\FilamentAuthenticationLogPlugin;
use App\Filament\AvatarProviders\AutenticatedUserAvatar;
use App\Filament\CustomWidgets\AppointmentStats;
use App\Filament\Resources\AppointmentResource;
use App\Filament\Resources\AppointmentTemplateResource;
use App\Filament\Widgets\CalendarWidget;
use App\Http\Middleware\AuthenticateAndCheckActive;
use App\Models\Setting;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Filament\Pages\Auth\PasswordReset\RequestPasswordReset;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;

class PersonalPanelProvider extends PanelProvider
{

    public function panel(Panel $panel): Panel
    {
        $panel
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->default()
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(MaxWidth::Full)
            ->id('personal')
            ->path('personal')
            ->login()
            ->passwordReset(RequestPasswordReset::class)
            ->colors([
                'primary' => '#581177',     // Lavanda claro 581177
                'secondary' => '#ecc4f4',   // Púrpura profundo
                'success' => '#10b981',     // Verde esmeralda (mantengo este para no romper alerts)
                'danger' => '#dc3545',      // Rojo clásico
                'warning' => '#ffc107',     // Amarillo warning
                'info' => '#0dcaf0',        // Azul info
                'light' => '#f5f5f5',       // Gris claro de fondo
                'dark' => '#000000',        // Negro
            ])
            ->resources([])
            ->defaultAvatarProvider(AutenticatedUserAvatar::class)
            ->plugins([
                FilamentFullCalendarPlugin::make()->config(
                    []
                ),
            ])
            ->pages([
                Pages\Dashboard::class,
                \App\Filament\Pages\Profile::class, // 👈 registra tu página
            ])
            // ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                AppointmentStats::class,
                CalendarWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                AuthenticateAndCheckActive::class,
            ])
            ->userMenuItems([
                MenuItem::make('profile')
                    ->label('Perfil')
                    ->url(fn() => \App\Filament\Pages\Profile::getUrl(panel: 'personal'))
                    ->icon('heroicon-o-user'),

                MenuItem::make('admin')
                    ->label('Ir a Administración')
                    ->url(url('/admin'))
                    ->icon('heroicon-o-cog')
                    ->visible(fn() => Auth::check() && Auth::user()->can_admin_panel), // ✅ visibilidad condicional

                MenuItem::make('home')
                    ->label('Ir a la Home')
                    ->url(url('/'))
                    ->icon('heroicon-o-globe-alt')
                    ->openUrlInNewTab(),
            ]);


        if (Schema::hasTable('settings')) {
            $settings = Setting::first();

            if ($settings && $settings->general) {
                $generalSettings = $settings->general;
                if (!empty($generalSettings->image) && $generalSettings->image != "[]") {
                    $panel->brandLogo(Storage::url(str_replace('"', '', $generalSettings->image)))
                        ->brandLogoHeight('3rem');
                } elseif (!empty($generalSettings->brand_name)) {
                    return $panel->brandName(str_replace('"', '', $generalSettings->brand_name));
                }
            }
        }

        return $panel;
    }

    // Método para registrar el menú del usuario
    public function boot()
    {
        Filament::serving(function () {

            Filament::registerUserMenuItems([
                'profile' => MenuItem::make()
                    ->label('Perfil')
                    ->url(route('filament.admin.pages.profile')) // Aquí también agregamos la URL
                    ->icon('heroicon-o-user'),
                'home' => MenuItem::make()
                    ->label('Ir a la Home')
                    ->url(url('/')) // tu landing page
                    ->icon('heroicon-o-globe-alt') // ícono globo terrestre
                    ->openUrlInNewTab(), // opcional: abre en nueva pestaña
            ]);
        });
    }
}
