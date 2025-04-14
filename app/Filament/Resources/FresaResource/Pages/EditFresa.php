<?php

namespace App\Filament\Resources\FresaResource\Pages;

use App\Filament\Resources\FresaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFresa extends EditRecord
{
    protected static string $resource = FresaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
