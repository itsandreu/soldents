<?php

namespace App\Filament\Clusters\Inventario\Resources\AnalogoMarcaResource\Pages;

use App\Filament\Clusters\Inventario\Resources\AnalogoMarcaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnalogoMarca extends EditRecord
{
    protected static string $resource = AnalogoMarcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
