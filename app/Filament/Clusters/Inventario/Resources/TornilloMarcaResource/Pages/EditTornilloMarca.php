<?php

namespace App\Filament\Clusters\Inventario\Resources\TornilloMarcaResource\Pages;

use App\Filament\Clusters\Inventario\Resources\TornilloMarcaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTornilloMarca extends EditRecord
{
    protected static string $resource = TornilloMarcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
