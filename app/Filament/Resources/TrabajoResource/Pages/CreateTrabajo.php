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

    protected ?string $heading = "Crear Trabajo";

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record->getKey()]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['piezas'] = array_merge(
            $data['cuadrante1'] ?? [],
            $data['cuadrante2'] ?? [],
            $data['cuadrante3'] ?? [],
            $data['cuadrante4'] ?? []
        );
        unset($data['cuadrante1'], $data['cuadrante2'], $data['cuadrante3'], $data['cuadrante4']);
    
        return $data;
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
