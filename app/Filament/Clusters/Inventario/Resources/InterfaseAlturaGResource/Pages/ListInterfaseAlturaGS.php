<?php

namespace App\Filament\Clusters\Inventario\Resources\InterfaseAlturaGResource\Pages;

use App\Filament\Clusters\Inventario\Resources\InterfaseAlturaGResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInterfaseAlturaGS extends ListRecords
{
    protected static string $resource = InterfaseAlturaGResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
