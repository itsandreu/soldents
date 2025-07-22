<?php

namespace App\Filament\Auth;

use Filament\Forms;
use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('email')
                ->label('Correo electrónico') // <-- Aquí cambias el label
                ->email()
                ->required()
                ->autofocus(),

            Forms\Components\TextInput::make('password')
                ->label('Contraseña') // <-- Aquí cambias el label
                ->password()
                ->required(),

            Forms\Components\Checkbox::make('remember')
                ->label('Recordarme'),
        ];
    }
}