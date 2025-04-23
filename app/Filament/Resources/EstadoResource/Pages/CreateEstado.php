<?php

namespace App\Filament\Resources\EstadoResource\Pages;

use App\Filament\Resources\EstadoResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateEstado extends CreateRecord
{
    protected static string $resource = EstadoResource::class;
    
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
            ->label('Añadir & Añadir otro')
            ->icon('heroicon-m-plus')->color(600);
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Cancelar')->color('warning')->icon('heroicon-m-x-mark');
    }
}
