<?php

namespace App\Filament\Resources\FacturaResource\Pages;

use App\Filament\Clusters\Gestion\Resources\FacturaResource\Widgets\FacturasApexChart;
use App\Filament\Clusters\Gestion\Resources\FacturaResource\Widgets\FacturasPorMesStat;
use App\Filament\Clusters\Gestion\Resources\FacturaResource\Widgets\GraficoFacturasChart;
use App\Filament\Clusters\Gestion\Resources\FacturaResource\Widgets\GraficoFacturasPrecioChart;
use App\Filament\Clusters\Gestion\Resources\FacturaResource\Widgets\GraficoFacturasProveedorChart;
use App\Filament\Clusters\Gestion\Resources\FaturaResource\Widgets\TotalFacturadoStat;
use App\Filament\Resources\FacturaResource;
use App\Filament\Resources\FacturaResource\Widgets\StatsFacturas;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListFacturas extends ListRecords
{
    protected static string $resource = FacturaResource::class;

    public function getHeaderWidgetsColumns(): int
    {
        return 14; // o 12 si quieres más columnas disponibles
    }
    protected function getHeaderWidgets(): array
    {
        return [
            // GraficoFacturasChart::class,
            // GraficoFacturasPrecioChart::class,
            // GraficoFacturasProveedorChart::class,
            TotalFacturadoStat::class,
        ];
    }
    protected function hasRecordPreview(): bool
    {
        return true;
    }

    protected function getRecordPreview(Model $record): string
    {
        return view('filament.resources.factura-resource.preview', [
            'record' => $record,
        ])->render();
    }

    protected function getTableRecordUrlUsing(): ?\Closure
    {
        return fn($record) => null;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Añadir Factura'),
        ];
    }
}
