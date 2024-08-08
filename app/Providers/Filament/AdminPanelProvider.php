<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EmailVerification;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\RequestPasswordReset;
use App\Livewire\MyProfileExtended;
use App\Settings\GeneralSettings;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Report\Cuti;
use Filament\Pages\Auth\Register;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Http\Controllers\Report\Cuti as PengajuanCuti;
use App\Http\Controllers\Report\Pindah as PengajuanPindah;
use App\Http\Controllers\Report\Aktif as PengajuanAktif;
use App\Http\Controllers\Report\Undur as PengajuanUndur;
use App\Http\Controllers\Report\Profesi as PengajuanProfesi;
use App\Http\Controllers\Verified;
use Illuminate\Support\Facades\Route;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Pages\PengajuanData;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->spa()
            ->id('admin')
            ->path('')
            ->colors([
                'primary' => Color::hex('#0e7490')
            ])
            ->registration(Register::class)
            ->login(Login::class)            
            ->passwordReset(RequestPasswordReset::class)
            ->emailVerification(EmailVerification::class)
            ->favicon(fn (GeneralSettings $settings) => Storage::url($settings->site_favicon))
            ->brandName(fn (GeneralSettings $settings) => $settings->brand_name)
            ->brandLogo(fn (GeneralSettings $settings) => Storage::url($settings->brand_logo))
            ->brandLogoHeight(fn (GeneralSettings $settings) => $settings->brand_logoHeight)
            ->colors(fn (GeneralSettings $settings) => $settings->site_theme)
            ->databaseNotifications()->databaseNotificationsPolling('30s')
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->sidebarCollapsibleOnDesktop()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->resources([
                config('filament-logger.activity_resource')
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // 'Report' => Cuti::route('/report'),
            ])
            ->routes(function () {
                //  add to /portal/*
                Route::get('/report/cuti/{id}', [PengajuanCuti::class, 'generatePDF']);
                Route::get('/validate/cuti/{id}', [Verified::class, 'cuti']);

                Route::get('/report/aktif/{id}', [PengajuanCuti::class, 'generatePDF']);
                Route::get('/validate/aktif/{id}', [Verified::class, 'aktif']);

                Route::get('/report/pindah/{id}', [PengajuanCuti::class, 'generatePDF']);
                Route::get('/validate/pindah/{id}', [Verified::class, 'pindah']);

                Route::get('/report/undur/{id}', [PengajuanCuti::class, 'generatePDF']);
                Route::get('/validate/undur/{id}', [Verified::class, 'undur']);

                Route::get('/report/profesi/{id}', [PengajuanCuti::class, 'generatePDF']);
                Route::get('/validate/profesi/{id}', [Verified::class, 'profesi']);
            })
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([                
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
            ])
            ->plugins([
                \BezhanSalleh\FilamentExceptions\FilamentExceptionsPlugin::make(),
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 2,
                        'sm' => 1
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
                \Jeffgreco13\FilamentBreezy\BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true,
                        shouldRegisterNavigation: false,
                        navigationGroup: 'Settings',
                        hasAvatars: true,
                        slug: 'my-profile'
                    )
                    ->myProfileComponents([
                        'personal_info' => MyProfileExtended::class,
                    ]),
            ]);
    }
}
