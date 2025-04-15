<?php

namespace App\Filament\Clusters\Inventario\Resources\InterfaseTipoResource\Pages;

use App\Filament\Clusters\Inventario\Resources\InterfaseTipoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInterfaseTipo extends EditRecord
{
    protected static string $resource = InterfaseTipoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
