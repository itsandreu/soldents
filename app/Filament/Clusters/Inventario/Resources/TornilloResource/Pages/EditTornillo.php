<?php

namespace App\Filament\Clusters\Inventario\Resources\TornilloResource\Pages;

use App\Filament\Clusters\Inventario\Resources\TornilloResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTornillo extends EditRecord
{
    protected static string $resource = TornilloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
