<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $title = 'Inicio';
    
    public function getColumns(): int|string|array
    {
        return 8;
    }
}
