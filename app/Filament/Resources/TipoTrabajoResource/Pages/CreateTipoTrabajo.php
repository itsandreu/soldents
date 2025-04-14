<?php

namespace App\Filament\Resources\TipoTrabajoResource\Pages;

use App\Filament\Resources\TipoTrabajoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTipoTrabajo extends CreateRecord
{
    protected static string $resource = TipoTrabajoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
