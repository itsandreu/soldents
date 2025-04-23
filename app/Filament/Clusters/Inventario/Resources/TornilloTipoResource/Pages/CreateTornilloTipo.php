<?php

namespace App\Filament\Clusters\Inventario\Resources\TornilloTipoResource\Pages;

use App\Filament\Clusters\Inventario\Resources\TornilloTipoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTornilloTipo extends CreateRecord
{
    protected static string $resource = TornilloTipoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
