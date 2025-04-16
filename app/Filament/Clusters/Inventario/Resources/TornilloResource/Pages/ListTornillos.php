<?php

namespace App\Filament\Clusters\Inventario\Resources\TornilloResource\Pages;

use App\Filament\Clusters\Inventario\Resources\TornilloResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTornillos extends ListRecords
{
    protected static string $resource = TornilloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
