<?php

namespace App\Filament\Clusters\Inventario\Resources\TornilloTipoResource\Pages;

use App\Filament\Clusters\Inventario\Resources\TornilloTipoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTornilloTipo extends EditRecord
{
    protected static string $resource = TornilloTipoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
