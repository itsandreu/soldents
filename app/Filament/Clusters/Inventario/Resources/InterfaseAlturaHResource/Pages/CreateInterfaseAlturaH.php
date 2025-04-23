<?php

namespace App\Filament\Clusters\Inventario\Resources\InterfaseAlturaHResource\Pages;

use App\Filament\Clusters\Inventario\Resources\InterfaseAlturaHResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInterfaseAlturaH extends CreateRecord
{
    protected static string $resource = InterfaseAlturaHResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
