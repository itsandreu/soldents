<?php

namespace App\Filament\Clusters\Inventario\Resources\AnalogoMarcaResource\Pages;

use App\Filament\Clusters\Inventario\Resources\AnalogoMarcaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnalogoMarcas extends ListRecords
{
    protected static string $resource = AnalogoMarcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
