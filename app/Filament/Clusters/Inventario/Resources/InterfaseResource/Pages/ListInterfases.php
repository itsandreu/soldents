<?php

namespace App\Filament\Clusters\Inventario\Resources\InterfaseResource\Pages;

use App\Filament\Clusters\Inventario\Resources\InterfaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInterfases extends ListRecords
{
    protected static string $resource = InterfaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
