<?php

namespace App\Filament\Resources\ClinicaResource\Pages;

use App\Filament\Resources\ClinicaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClinica extends CreateRecord
{
    protected static string $resource = ClinicaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
