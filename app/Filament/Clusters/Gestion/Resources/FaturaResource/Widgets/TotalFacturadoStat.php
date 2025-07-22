<?php

namespace App\Filament\Clusters\Gestion\Resources\FaturaResource\Widgets;

use Filament\Widgets\Widget;

class TotalFacturadoStat extends Widget
{
    protected static string $view = 'filament.clusters.gestion.resources.fatura-resource.widgets.total-facturado-stat';
    public function getColumnSpan(): int|string
    {
        return 14; // puede ser 1 o 2, según el layout
    }
}
