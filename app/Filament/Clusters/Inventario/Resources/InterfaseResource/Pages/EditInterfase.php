<?php

namespace App\Filament\Clusters\Inventario\Resources\InterfaseResource\Pages;

use App\Filament\Clusters\Inventario\Resources\InterfaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInterfase extends EditRecord
{
    protected static string $resource = InterfaseResource::class;

    protected ?string $heading = "Editar Interfase";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Eliminar'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
