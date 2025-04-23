<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use Illuminate\Support\Facades\Auth;


class Inventario extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?String $navigationLabel = 'Inventario';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->role === 'admin';
    }
}  
