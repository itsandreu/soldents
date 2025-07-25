<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\CalendarWidget;
use App\Filament\Widgets\FacturasTableWidget;
use App\Filament\Widgets\TrabajosTableWidget;
use App\Models\Clinica;
use App\Models\User;
use App\Filament\Widgets\trabajosWidget;
use App\Providers\FilamentUserAvatarProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;
use App\Filament\Resources\UserResource;
use Filament\Pages\Auth\EditProfile;
use Filament\Facades\Filament;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use App\Filament\Auth\Login;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'amber' => Color::Amber,
                'red' => Color::Red,
                'sky' => Color::Sky,
                'violet' => Color::Violet,
                'green' => Color::Green,
                'black' => Color::hex('#001219'),
                'aqua' => Color::hex('#00a896'),
                'description' => Color::hex('#6c757d')
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->maxContentWidth(MaxWidth::Full)
            ->brandLogo(asset('storage/soldents.png'))->brandLogoHeight('3.5rem')
            ->navigationGroups([
                NavigationGroup::make()
                     ->label('Work')
                     ->icon('heroicon-o-briefcase'),
                NavigationGroup::make()
                    ->label('Inventario')
                    ->icon('heroicon-o-cloud'),
                NavigationGroup::make()
                    ->label('Ajustes')
                    ->icon('heroicon-o-cog-8-tooth')->collapsed(),
            ])
            ->plugins([
                FilamentBackgroundsPlugin::make()
                ->imageProvider(
                    MyImages::make()
                        ->directory('backgrounds/')
                ),
                AuthUIEnhancerPlugin::make()
                    ->formPanelPosition('left')
                        ->formPanelWidth('40%')
                        ->formPanelBackgroundColor(Color::hex('#34a19a'))
                        ->emptyPanelBackgroundColor(Color::hex('#f0f0f0'))
                        ->emptyPanelBackgroundImageUrl(asset('backgrounds/loginfondo.png'))
                        ->emptyPanelBackgroundImageOpacity('70%'),
                FilamentApexChartsPlugin::make()
            ])
            ->sidebarWidth('50rem')
            ->collapsedSidebarWidth('2.5rem')
            ->darkMode(false)
            ->sidebarCollapsibleOnDesktop()
            ->collapsibleNavigationGroups(true)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                
            ])
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            // ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                CalendarWidget::class,
                trabajosWidget::class,
                TrabajosTableWidget::class,
                FacturasTableWidget::class
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
            ])->breadcrumbs(true)
            ->authMiddleware([
                Authenticate::class,
            ])
            ->sidebarWidth('15rem')
            ->plugin(
            FilamentFullCalendarPlugin::make()
                ->schedulerLicenseKey(false)
                ->selectable()
                ->editable()
                ->timezone(config('app.timezone')) // Configura la zona horaria de la aplicación
                ->locale(app()->getLocale()) // Establece el idioma de la aplicación
                ->plugins(['dayGrid', 'timeGrid', 'interaction']) // Habilita los plugins necesarios
                ->config([
                    'initialView' => 'dayGridMonth', // Vista predeterminada del calendario
                    'headerToolbar' => [
                        'left' => 'prev,next today',
                        'center' => 'title',
                        'right' => 'dayGridMonth,timeGridWeek,timeGridDay'
                    ],
                    'selectOverlap' => false,
                    'editable' => true,
                    'eventLimit' => true, // Carga eventos desde una API o ruta
                ])
            )->userMenuItems([
                'profile' => MenuItem::make()
                ->url(fn () => UserResource::getUrl('edit', ['record' => Filament::auth()->user()->id]))
                ->label('Mi Perfil'),
                // ...
            ])->profile(isSimple: true)
            ->breadcrumbs(true)
            ->font('Poppins')
            ->spa();
    }
}
