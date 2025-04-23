<?php

namespace App\Providers;

use Filament\AvatarProviders\Contracts\AvatarProvider;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Contracts\Auth\Authenticatable;

class FilamentUserAvatarProvider implements HasAvatar
{
    public function getFilamentAvatarUrl(Authenticatable $user): ?string
    {
        return $user->foto_url 
            ? asset($user->foto_url) 
            : asset('images/default-user.png'); // Ruta a tu imagen por defecto
    }
}