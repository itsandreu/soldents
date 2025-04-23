<?php

namespace App\Filament\Clusters\Inventario\Resources\AnalogoResource\Pages;

use App\Filament\Clusters\Inventario\Resources\AnalogoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateAnalogo extends CreateRecord
{
    protected static string $resource = AnalogoResource::class;

    protected ?string $heading = "Añadir Análogo";

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

    protected function getCancelFormAction(): \Filament\Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('Cancelar')->color('warning')->icon('heroicon-m-x-mark');
    }
}
