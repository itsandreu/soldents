<?php

namespace App\Filament\Resources\ClinicaResource\Pages;

use App\Filament\Resources\ClinicaResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateClinica extends CreateRecord
{
    protected static string $resource = ClinicaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->label('Añadir')
            ->icon('heroicon-m-plus');
    }
    
    protected function getCreateAnotherFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Añadir & Añadir otra')
            ->icon('heroicon-m-plus')->color(600);
    }

    protected function getCancelFormAction(): \Filament\Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('Cancelar')->color('warning')->icon('heroicon-m-x-mark');
    }
}
