<?php

namespace App\Filament\Clusters\Gestion\Resources\FacturaResource\Widgets;

use App\Models\Factura;
use Filament\Widgets\ChartWidget;

class GraficoFacturasProveedorChart extends ChartWidget
{
    protected static ?string $heading = 'Facturas por proveedor';

    protected function getData(): array
    {
        $facturasPorProveedor = Factura::with('supplier')
            ->selectRaw('supplier_id, COUNT(*) as total')
            ->groupBy('supplier_id')
            ->get();

        $labels = [];
        $data = [];

        foreach ($facturasPorProveedor as $item) {
            $labels[] = optional($item->supplier)->nombre ?? 'Sin proveedor';
            $data[] = $item->total;
        }

        // Paleta de colores fija
        $colorPalette = [
            '#FF9F40', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#E7E9ED', '#00CED1', '#A0522D',
            '#5F9EA0', '#D2691E', '#8A2BE2', '#FF6384',
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

    protected function getType(): string
    {
        return 'doughnut';
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

    public function getColumnSpan(): int|string
    {
        return 3;
    }
}
