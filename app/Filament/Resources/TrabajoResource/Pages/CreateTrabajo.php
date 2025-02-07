<?php

namespace App\Filament\Resources\TrabajoResource\Pages;

use App\Filament\Resources\TrabajoResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\CreateRecord;

class CreateTrabajo extends CreateRecord
{
    protected static string $resource = TrabajoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record->getKey()]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
       dd( $data);
        // = auth()->id();
 
    }
}
