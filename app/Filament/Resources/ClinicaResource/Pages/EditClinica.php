<?php

namespace App\Filament\Resources\ClinicaResource\Pages;

use App\Filament\Resources\ClinicaResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditClinica extends EditRecord
{
    protected static string $resource = ClinicaResource::class;

    protected ?string $heading = "Editar Clínica";

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Eliminar Clínica'),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Guardar')->icon('heroicon-m-cloud-arrow-down');
    }

    protected function getCancelFormAction(): \Filament\Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('Cancelar')->color('warning')->icon('heroicon-m-x-mark');
    }
}
