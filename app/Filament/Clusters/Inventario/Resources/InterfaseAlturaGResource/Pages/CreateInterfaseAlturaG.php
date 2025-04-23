<?php

namespace App\Filament\Clusters\Inventario\Resources\InterfaseAlturaGResource\Pages;

use App\Filament\Clusters\Inventario\Resources\InterfaseAlturaGResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInterfaseAlturaG extends CreateRecord
{
    protected static string $resource = InterfaseAlturaGResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
