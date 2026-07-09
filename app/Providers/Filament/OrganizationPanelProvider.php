<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\RegisterOrganization;
use App\Models\Organization;
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
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class OrganizationPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('organization')
            ->path('organization')
            ->colors([
                'primary' => Color::Teal,
            ])
            ->login()
            ->sidebarCollapsibleOnDesktop()
            ->registration(RegisterOrganization::class)
            ->tenant(Organization::class, slugAttribute: 'id')
            ->brandLogo(asset('assets/img_2.png'))
            ->brandLogoHeight('60px')
            ->discoverResources(in: app_path('Filament/Organization/Resources'), for: 'App\Filament\Organization\Resources')
            ->discoverPages(in: app_path('Filament/Organization/Pages'), for: 'App\Filament\Organization\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Organization/Widgets'), for: 'App\Filament\Organization\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
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
            ])->plugins([
                AuthDesignerPlugin::make()
                    ->defaults(fn(AuthPageConfig $config) => $config
                    ->media(asset('assets/img_1.png')
                    ))
                    ->registration(fn(AuthPageConfig $config) => $config
                        ->media(asset('assets/img_1.png'))
                        ->usingPage(RegisterOrganization::class)
                        ->mediaPosition(MediaPosition::Cover)
                        ->mediaSize('60%')
                    )
                    ->login(fn(AuthPageConfig $config) => $config
                        ->media(asset('assets/img_1.png'))
                        ->mediaPosition(MediaPosition::Cover)
                        ->mediaSize('60%') // Media takes 50% width
                    )

            ]);
    }
}
