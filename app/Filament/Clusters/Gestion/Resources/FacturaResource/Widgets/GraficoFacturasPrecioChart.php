<?php

namespace App\Filament\Clusters\Gestion\Resources\FacturaResource\Widgets;

use App\Models\Factura;
use Filament\Widgets\ChartWidget;

class GraficoFacturasPrecioChart extends ChartWidget
{
    protected static ?string $heading = 'Facturas por precio';

    protected function getData(): array
    {
        $facturas = Factura::select('nombre', 'precio')
            ->whereNotNull('precio')
            ->get();

        $labels = $facturas->pluck('nombre')->toArray();
        $data = $facturas->pluck('precio')->toArray();

        $colorPalette = [
            '#4BC0C0',
            '#36A2EB',
            '#FFCE56',
            '#FF6384',
            '#9966FF',
            '#FF9F40',
            '#E7E9ED',
            '#A0522D',
            '#5F9EA0',
            '#D2691E',
            '#8A2BE2',
            '#00CED1',
            '#4682B4',
            '#9ACD32',
            '#8B0000',
            '#FFD700',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Importe por factura',
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
                    'grid' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'display' => false,
                    'grid' => [
                        'display' => false,
                    ],
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
        return 3;
    }
}
