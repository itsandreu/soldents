<?php

namespace App\Filament\Resources\FacturaResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Factura;
class StatsFacturas extends BaseWidget
{

    public function getColumnSpan(): int|string
    {
        return 1; // puede ser 1 o 2, según el layout
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Total facturado', number_format(Factura::sum('precio'), 2) . ' €')
                ->chart([7, 2, 12, 3, 17, 4, 20])
                ->color('success')
                ->description('Suma total del importe de todas las faturas'),
        ];
    }
}
