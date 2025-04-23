<?php

namespace App\Filament\Clusters\Inventario\Resources\AnalogoResource\Pages;

use App\Filament\Clusters\Inventario\Resources\AnalogoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnalogos extends ListRecords
{
    protected static string $resource = AnalogoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Añadir Stock'),
        ];
    }
}
