<?php

namespace App\Providers\Filament;

use App\Models\Clinica;
use App\Models\User;
use App\Filament\Widgets\trabajosWidget;
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
                'green' => Color::Green
            ])->viteTheme('resources/css/filament/admin/theme.css')
            ->maxContentWidth(MaxWidth::Full)
            ->navigationGroups([
                NavigationGroup::make()
                     ->label('Work')
                     ->icon('heroicon-o-briefcase'),
                NavigationGroup::make()
                    ->label('Inventario')
                    ->icon('heroicon-o-cloud'),
                NavigationGroup::make()
                    ->label('Recursos')
                    ->icon('heroicon-o-cog-8-tooth')->collapsed(),
            ])->darkMode(false)
            ->sidebarCollapsibleOnDesktop()
            ->collapsibleNavigationGroups(true)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                trabajosWidget::class
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
            ])->breadcrumbs(false)
            ->authMiddleware([
                Authenticate::class,
            ])
            ->sidebarWidth('15rem')->plugin(
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
            );
    }
}
