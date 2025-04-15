<?php

namespace App\Filament\Clusters\Inventario\Resources\InterfaseTipoResource\Pages;

use App\Filament\Clusters\Inventario\Resources\InterfaseTipoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInterfaseTipos extends ListRecords
{
    protected static string $resource = InterfaseTipoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
