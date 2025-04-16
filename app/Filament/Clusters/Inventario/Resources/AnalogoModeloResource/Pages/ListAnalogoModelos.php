<?php

namespace App\Filament\Clusters\Inventario\Resources\AnalogoModeloResource\Pages;

use App\Filament\Clusters\Inventario\Resources\AnalogoModeloResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnalogoModelos extends ListRecords
{
    protected static string $resource = AnalogoModeloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
