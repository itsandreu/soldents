<?php

namespace App\Filament\Clusters\Inventario\Resources\InterfaseAlturaHResource\Pages;

use App\Filament\Clusters\Inventario\Resources\InterfaseAlturaHResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInterfaseAlturaH extends EditRecord
{
    protected static string $resource = InterfaseAlturaHResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
