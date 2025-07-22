<?php

namespace App\Filament\Clusters\Gestion\Resources\FacturaResource\Widgets;

use App\Models\Factura;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class GraficoFacturasChart extends ChartWidget
{
    protected static ?string $heading = 'Facturas por mes';

    protected function getData(): array
    {
        // Consulta agrupada por mes
        $facturasPorMes = Factura::selectRaw('MONTH(fecha_factura) as mes, COUNT(*) as total')
            ->whereNotNull('fecha_factura')
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $data = [];
        $labels = [];

        foreach ($facturasPorMes as $item) {
            $labels[] = Carbon::create()->month($item->mes)->locale('es')->translatedFormat('F');
            $data[] = $item->total;
        }

        // Paleta de colores fija
        $colorPalette = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#FF9F40', '#E7E9ED', '#A0522D',
            '#5F9EA0', '#D2691E', '#8A2BE2', '#00CED1',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Facturas',
                    'data' => $data,
                    'backgroundColor' => array_slice($colorPalette, 0, count($data)),
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    public function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'x' => [
                    'display' => false,
                    'grid' => ['display' => false],
                ],
                'y' => [
                    'display' => false,
                    'grid' => ['display' => false],
                ],
            ],
        ];
    }
    protected function getType(): string
    {
        return 'doughnut';
    }

    public function getColumnSpan(): int|string
    {
        return 3; // ocupa solo un tercio del ancho del recurso
    }
}
