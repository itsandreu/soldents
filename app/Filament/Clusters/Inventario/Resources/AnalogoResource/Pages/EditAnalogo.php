<?php

namespace App\Filament\Clusters\Inventario\Resources\AnalogoResource\Pages;

use App\Filament\Clusters\Inventario\Resources\AnalogoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnalogo extends EditRecord
{
    protected static string $resource = AnalogoResource::class;

    protected ?string $heading = "Editar Análogo";

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
