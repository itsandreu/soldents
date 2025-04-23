<?php

namespace App\Filament\Clusters\Inventario\Resources\InterfaseTipoResource\Pages;

use App\Filament\Clusters\Inventario\Resources\InterfaseTipoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInterfaseTipo extends CreateRecord
{
    protected static string $resource = InterfaseTipoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
