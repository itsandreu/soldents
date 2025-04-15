<?php

namespace App\Filament\Clusters\Inventario\Resources\InterfaseAlturaGResource\Pages;

use App\Filament\Clusters\Inventario\Resources\InterfaseAlturaGResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInterfaseAlturaG extends EditRecord
{
    protected static string $resource = InterfaseAlturaGResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
