<?php

namespace App\Filament\Clusters\Inventario\Resources\InterfaseDiametroResource\Pages;

use App\Filament\Clusters\Inventario\Resources\InterfaseDiametroResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInterfaseDiametros extends ListRecords
{
    protected static string $resource = InterfaseDiametroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
