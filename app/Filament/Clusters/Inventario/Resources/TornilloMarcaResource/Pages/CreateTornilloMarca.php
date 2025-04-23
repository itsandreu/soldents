<?php

namespace App\Filament\Clusters\Inventario\Resources\TornilloMarcaResource\Pages;

use App\Filament\Clusters\Inventario\Resources\TornilloMarcaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTornilloMarca extends CreateRecord
{
    protected static string $resource = TornilloMarcaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
