<?php

namespace App\Filament\Clusters\Inventario\Resources\AnalogoDiametroResource\Pages;

use App\Filament\Clusters\Inventario\Resources\AnalogoDiametroResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAnalogoDiametro extends CreateRecord
{
    protected static string $resource = AnalogoDiametroResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
