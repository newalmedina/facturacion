<?php

namespace App\Providers\Filament;

use App\Filament\AvatarProviders\AutenticatedUserAvatar;
use App\Filament\Pages\Settings\Settings;
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
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Storage;
use Outerweb\FilamentSettings\Filament\Plugins\FilamentSettingsPlugin;

class AdminPanelProvider extends PanelProvider
{

    public function panel(Panel $panel): Panel
    {
        $panel
            ->default()
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(MaxWidth::Full)
            ->id('admin')
            ->path('admin')
            ->login()

            ->colors([
                'primary' => Color::Amber,
            ])
            ->defaultAvatarProvider(AutenticatedUserAvatar::class)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->plugins([
                FilamentSettingsPlugin::make()
                    ->pages([
                        Settings::class,
                    ])
            ])
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ]);

        $settings = Setting::first();
        $generalSettings = $settings->general;
        if (!empty($generalSettings->image)) {
            $panel->brandLogo(Storage::url(str_replace('"', '', $generalSettings->image)))
                ->brandLogoHeight('3rem');
        } else if (!empty($generalSettings->brand_name)) {
            return $panel->brandName(str_replace('"', '', $generalSettings->brand_name));
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
            ]);
        });
    }
}
