<?php

namespace App\Providers;

use App\Models\Persona;
use App\Observers\PersonaObserver;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
	    URL::forceHttps(true);
        Persona::observe(PersonaObserver::class);
        Carbon::setLocale(App::getLocale());

    }
}
