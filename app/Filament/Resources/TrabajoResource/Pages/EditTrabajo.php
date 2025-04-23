<?php

namespace App\Filament\Resources\TrabajoResource\Pages;

use App\Filament\Resources\TrabajoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditTrabajo extends EditRecord
{
    protected static string $resource = TrabajoResource::class;

    protected ?string $heading = "Editar Trabajo";


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Eliminar'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['cuadrante1'] = [];
        $data['cuadrante2'] = [];
        $data['cuadrante3'] = [];
        $data['cuadrante4'] = [];
    
        Log::info('Datos originales:', $data);
    
        if (!is_array($data['piezas'])) {
            $data['piezas'] = [];
        }
    
        foreach ($data['piezas'] as $pieza) {
            if ($this->esCuadrante(1, $pieza)) {
                $data['cuadrante1'][] = $pieza;
            } elseif ($this->esCuadrante(2, $pieza)) {
                $data['cuadrante2'][] = $pieza;
            } elseif ($this->esCuadrante(3, $pieza)) {
                $data['cuadrante3'][] = $pieza;
            } elseif ($this->esCuadrante(4, $pieza)) {
                $data['cuadrante4'][] = $pieza;
            }
        }
    
        Log::info('Datos después de mutar:', $data);
    
        return $data;
    }
    
    private function esCuadrante(int $cuadrante, int $pieza): bool
    {
        return match ($cuadrante) {
            1 => $pieza >= 11 && $pieza <= 18, 
            2 => $pieza >= 21 && $pieza <= 28, 
            3 => $pieza >= 31 && $pieza <= 38, 
            4 => $pieza >= 41 && $pieza <= 48, 
            default => false,
        };
    }

    protected function getSaveFormAction(): \Filament\Actions\Action
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
