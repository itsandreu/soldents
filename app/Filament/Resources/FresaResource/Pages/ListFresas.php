<?php

namespace App\Filament\Resources\FresaResource\Pages;

use App\Filament\Resources\FresaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFresas extends ListRecords
{
    protected static string $resource = FresaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Añadir Stock'),
        ];
    }
}
