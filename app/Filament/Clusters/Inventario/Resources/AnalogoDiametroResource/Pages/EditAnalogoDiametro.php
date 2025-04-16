<?php

namespace App\Filament\Clusters\Inventario\Resources\AnalogoDiametroResource\Pages;

use App\Filament\Clusters\Inventario\Resources\AnalogoDiametroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnalogoDiametro extends EditRecord
{
    protected static string $resource = AnalogoDiametroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
