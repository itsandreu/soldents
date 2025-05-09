<?php

namespace App\Filament\Resources\DiscoResource\Pages;

use App\Filament\Resources\DiscoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListDiscos extends ListRecords
{
    protected static string $resource = DiscoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Añadir Stock'),
        ];
    }

}
