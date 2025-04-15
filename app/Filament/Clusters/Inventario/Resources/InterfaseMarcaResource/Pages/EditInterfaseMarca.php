<?php

namespace App\Filament\Clusters\Inventario\Resources\InterfaseMarcaResource\Pages;

use App\Filament\Clusters\Inventario\Resources\InterfaseMarcaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInterfaseMarca extends EditRecord
{
    protected static string $resource = InterfaseMarcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
