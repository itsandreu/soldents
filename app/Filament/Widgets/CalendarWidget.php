<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\TrabajoResource;
use App\Models\Clinica;
use App\Models\Paciente;
use App\Models\Persona;
use App\Models\TipoTrabajo;
use App\Models\Trabajo;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{

    public Model | string | null $model = Trabajo::class;

    protected int | string | array $columnSpan = '5';

    protected function headerActions(): array
    {
        return [
            
        ];
    }
    public function fetchEvents(array $fetchInfo): array
    {
        return Trabajo::query()
            ->where('salida', '>=', $fetchInfo['start'])
            ->where('salida', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (Trabajo $event) {
                $paciente = Paciente::where('id',$event->paciente_id)->first();
                $persona = Persona::where('id',$paciente->persona_id)->first();
                $clinica = Clinica::where('id',$persona->clinica_id)->first();
                $descripcion = $event->descripcion ?? 'No hay descripción';
                return [
                    'title' => $persona->nombre . " $persona->apellidos" . " - " . $clinica->nombre,
                    'start' => $event->salida,
                    'end' => $event->salida,
                    'url' => TrabajoResource::getUrl(name: 'edit', parameters: ['record' => $event]),
                    'shouldOpenUrlInNewTab' => true,
                    'backgroundColor' =>'#3788d8',
                ];
            })
            ->all();
    }


    public function config(): array
    {
        return [
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'dayGridMonth,dayGridWeek,dayGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
        ];
    }



    
}


