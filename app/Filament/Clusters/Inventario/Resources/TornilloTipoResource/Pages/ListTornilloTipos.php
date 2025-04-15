<?php

namespace App\Filament\Clusters\Inventario\Resources\TornilloTipoResource\Pages;

use App\Filament\Clusters\Inventario\Resources\TornilloTipoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTornilloTipos extends ListRecords
{
    protected static string $resource = TornilloTipoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
