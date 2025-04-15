<?php

namespace App\Filament\Clusters\Inventario\Resources\AnalogoDiametroResource\Pages;

use App\Filament\Clusters\Inventario\Resources\AnalogoDiametroResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnalogoDiametros extends ListRecords
{
    protected static string $resource = AnalogoDiametroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
