<?php

namespace App\Filament\Resources\ResinaResource\Pages;

use App\Filament\Resources\ResinaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResina extends EditRecord
{
    protected static string $resource = ResinaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
