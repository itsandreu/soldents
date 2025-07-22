<?php

namespace App\Filament\Clusters\Gestion\Resources\FacturaResource\Widgets;

use App\Models\Factura;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class FacturasPorMesStat extends Widget
{
    protected static string $view = 'filament.clusters.gestion.resources.factura-resource.widgets.facturas-por-mes-stat';

protected function getViewData(): array
    {
        $facturas = Factura::select(
                DB::raw('MONTH(fecha_factura) as mes'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];

        $labels = [];
        $data = [];

        foreach ($meses as $n => $nombre) {
            $labels[] = $nombre;
            $data[] = $facturas->firstWhere('mes', $n)?->total ?? 0;
        }

        return compact('labels', 'data');
    }

    public function getColumnSpan(): int|string
    {
        return 5; // puede ser 1 o 2, según el layout
    }
}
