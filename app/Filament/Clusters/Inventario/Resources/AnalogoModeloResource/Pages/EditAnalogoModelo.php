<?php

namespace App\Filament\Clusters\Inventario\Resources\AnalogoModeloResource\Pages;

use App\Filament\Clusters\Inventario\Resources\AnalogoModeloResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnalogoModelo extends EditRecord
{
    protected static string $resource = AnalogoModeloResource::class;

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
