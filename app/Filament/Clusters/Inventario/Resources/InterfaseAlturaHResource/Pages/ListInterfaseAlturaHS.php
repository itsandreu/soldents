<?php

namespace App\Filament\Clusters\Inventario\Resources\InterfaseAlturaHResource\Pages;

use App\Filament\Clusters\Inventario\Resources\InterfaseAlturaHResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInterfaseAlturaHS extends ListRecords
{
    protected static string $resource = InterfaseAlturaHResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
