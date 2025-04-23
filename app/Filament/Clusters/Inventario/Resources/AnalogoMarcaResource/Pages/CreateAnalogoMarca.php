<?php

namespace App\Filament\Clusters\Inventario\Resources\AnalogoMarcaResource\Pages;

use App\Filament\Clusters\Inventario\Resources\AnalogoMarcaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAnalogoMarca extends CreateRecord
{
    protected static string $resource = AnalogoMarcaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
