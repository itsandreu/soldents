<?php

namespace App\Filament\Clusters\Inventario\Resources\InterfaseDiametroResource\Pages;

use App\Filament\Clusters\Inventario\Resources\InterfaseDiametroResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInterfaseDiametro extends CreateRecord
{
    protected static string $resource = InterfaseDiametroResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
