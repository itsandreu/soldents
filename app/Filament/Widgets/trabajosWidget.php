<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Trabajo;

class trabajosWidget extends Widget
{
    protected static string $view = 'filament.widgets.trabajos-widget';

    protected int|string|array $columnSpan = '3';
    public function render(): \Illuminate\View\View
    {
        // Obtén los trabajos, puedes personalizar esta consulta según lo que necesites
        // $trabajos = Trabajo::latest()->limit(10)->get();
        $trabajos = Trabajo::whereBetween('salida', [
                now()->startOfDay(),
                now()->addDays(30)->endOfDay()
            ])
            ->orderBy('salida', 'asc')
            ->get();

        // Pasa los datos a la vista
        return view('filament.widgets.trabajos-widget', [
            'trabajos' => $trabajos,  // Pasa los trabajos a la vista
        ]);
    }
}
