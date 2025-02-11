<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Trabajo;

class trabajosWidget extends Widget
{
    protected static string $view = 'filament.widgets.trabajos-widget';
    public function render(): \Illuminate\View\View
    {
        // Obtén los trabajos, puedes personalizar esta consulta según lo que necesites
        $trabajos = Trabajo::latest()->limit(5)->get();  // Aquí obtenemos los 5 trabajos más recientes

        // Pasa los datos a la vista
        return view('filament.widgets.trabajos-widget', [
            'trabajos' => $trabajos,  // Pasa los trabajos a la vista
        ]);
    }
}
