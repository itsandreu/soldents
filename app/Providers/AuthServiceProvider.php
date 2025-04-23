<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\Disco;
use App\Policies\DiscoPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Las policies del modelo para la aplicación.
     */
    protected $policies = [
        Disco::class => DiscoPolicy::class,
    ];

    /**
     * Registra cualquier policy de autenticación/autorización.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
