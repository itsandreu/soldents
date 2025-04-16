<?php

namespace App\Filament\Clusters\Inventario\Resources\TornilloModeloResource\Pages;

use App\Filament\Clusters\Inventario\Resources\TornilloModeloResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTornilloModelo extends EditRecord
{
    protected static string $resource = TornilloModeloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
