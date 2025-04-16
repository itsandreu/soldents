<?php

namespace App\Filament\Clusters\Inventario\Resources\TornilloModeloResource\Pages;

use App\Filament\Clusters\Inventario\Resources\TornilloModeloResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTornilloModelos extends ListRecords
{
    protected static string $resource = TornilloModeloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
