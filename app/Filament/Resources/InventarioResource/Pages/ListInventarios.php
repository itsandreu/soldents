<?php

namespace App\Filament\Resources\InventarioResource\Pages;

use App\Filament\Resources\InventarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventarios extends ListRecords
{
    protected static string $resource = InventarioResource::class;

    protected static ?string $title = 'Stock';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Añadir Stock'),
        ];
    }



    public function getBreadcrumb(): string
    {
        return 'Stock';
    }
}
