<?php

namespace App\Filament\Clusters\Inventario\Resources\TornilloModeloResource\Pages;

use App\Filament\Clusters\Inventario\Resources\TornilloModeloResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTornilloModelo extends CreateRecord
{
    protected static string $resource = TornilloModeloResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
