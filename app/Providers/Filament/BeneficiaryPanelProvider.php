<?php

namespace App\Providers\Filament;

use App\Filament\Beneficiary\Pages\Auth\CustomBeneficiaryLogin;
use App\Filament\Pages\Auth\RegisterOrganization;
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Data\AuthPageConfig;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class BeneficiaryPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('beneficiary')
            ->path('beneficiary/portal')
            ->viteTheme('resources/css/filament/beneficiary/theme.css')
            ->login()
            ->navigation(false)
            ->maxContentWidth(Width::Full)
            ->authGuard('beneficiary_guard') // سنعرف هذا الـ Guard في الخطوة القادمة
            ->colors([
                'primary' => Color::Teal,
            ])
            ->brandLogoHeight('60px')
            ->brandLogo(asset('assets/img_2.png'))
            ->discoverResources(in: app_path('Filament/Beneficiary/Resources'), for: 'App\Filament\Beneficiary\Resources')
            ->discoverPages(in: app_path('Filament/Beneficiary/Pages'), for: 'App\Filament\Beneficiary\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Beneficiary/Widgets'), for: 'App\Filament\Beneficiary\Widgets')
            ->widgets([

            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                AuthDesignerPlugin::make()
                    ->defaults(fn(AuthPageConfig $config) => $config
                        ->media(asset('assets/img_1.png')
                        ))
                    ->login(fn(AuthPageConfig $config) => $config
                        ->media(asset('assets/img_1.png'))
                        ->usingPage(CustomBeneficiaryLogin::class)
                        ->mediaPosition(MediaPosition::Cover)
                        ->mediaSize('60%') // Media takes 50% width
                    )
            ]);
    }
}
