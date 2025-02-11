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
                return [
                    'title' => $persona->nombre . " $persona->apellidos" . " - " . $clinica->nombre,
                    'start' => $event->salida,
                    'end' => $event->salida,
                    'url' => TrabajoResource::getUrl(name: 'edit', parameters: ['record' => $event]),
                    'shouldOpenUrlInNewTab' => true,
                    'backgroundColor' => $event->color_boca ?? '#3788d8',
                ];
            })
            ->all();
    }

    // public function getFormSchema(): array
    // {
    //     return [
    //         Section::make()
    //                 ->columns([
    //                     'sm' => 3,
    //                     'xl' => 3,
    //                     '2xl' => 3,
    //                 ])->schema([
    //                     Select::make('tipo_trabajo_id')->options(TipoTrabajo::all()->pluck('nombre', 'id'))->searchable()->preload(),
    //                     Select::make('paciente_id')
    //                         ->label('Paciente')
    //                         ->options(function () {
    //                             return Paciente::all()->mapWithKeys(function ($paciente) {
    //                                 return [$paciente->id => $paciente->persona->nombre];
    //                             })->toArray();
    //                         })->searchable()->columnSpan(1),
    //                     Select::make('color_boca')
    //                         ->options([
    //                             "A1" => "A1",
    //                             "A2" => "A2",
    //                             "A3" => "A3",
    //                             "A3.5" => "A3.5",
    //                             "A4" => "A4",
    //                             "B1" => "B1",
    //                             "B2" => "B2",
    //                             "B3" => "B3",
    //                             "B4" => "B4",
    //                             "C1" => "C1",
    //                             "C2" => "C2",
    //                             "C3" => "C3",
    //                             "C4" => "C4",
    //                             "D1" => "D1",
    //                             "D2" => "D2",
    //                             "D3" => "D3",
    //                             "D4" => "D4",
    //                             "OM1" => "OM1",
    //                             "OM2" => "OM2",
    //                             "OM3" => "OM3",
    //                         ])->searchable()
    //                         ->label("Color")->columnSpan(1),
    //                     Textarea::make("descripcion")->label("Descripción")->rows(5)->columnSpan(3),
    //                 ])->columnSpan(2),
    //             Section::make()
    //                 ->columns([
    //                     'sm' => 1,
    //                     'xl' => 1,
    //                     '2xl' => 1,
    //                 ])->schema([
    //                     DateTimePicker::make('entrada')->columnSpan(1),
    //                     DateTimePicker::make('salida')->columnSpan(1),
    //                     Select::make('estado_id')->relationship('estado', 'nombre')->columnSpan(1),
    //                 ])->columnSpan(1),
    //             Section::make("Piezas")->description("Selecciona los dientes de cada cuadrante para este trabajo.")
    //                 ->columns([
    //                     'sm' => 2,
    //                     'xl' => 2,
    //                     '2xl' => 2,
    //                 ])->schema([
    //                     Section::make([
    //                         CheckboxList::make('cuadrante1')->label(" ")
    //                             ->options(['18' => '18', '17' => '17', '16' => '16', '15' => '15', '14' => '14', '13' => '13', '12' => '12', '11' => '11',])
    //                             ->columns(8)
    //                             ->bulkToggleable()
    //                     ])->columnSpan(1),
    //                     Section::make([
    //                         CheckboxList::make('cuadrante2')->label(" ")
    //                             ->options(['21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28',])
    //                             ->columns(8)
    //                             ->bulkToggleable()
    //                     ])->columnSpan(1),
    //                     Section::make([
    //                         CheckboxList::make('cuadrante4')->label(" ")
    //                             ->options(['48' => '48', '47' => '47', '46' => '46', '45' => '45', '44' => '44', '43' => '43', '42' => '42', '41' => '41',])
    //                             ->columns(8)
    //                             ->bulkToggleable()
    //                     ])->columnSpan(1),
    //                     Section::make([
    //                         CheckboxList::make('cuadrante3')->label(" ")
    //                             ->options(['31' => '31','32' => '32','33' => '33','34' => '34','35' => '35','36' => '36','37' => '37','38' => '38',])
    //                             ->columns(8)
    //                             ->bulkToggleable()
    //                     ])->columnSpan(1),
    //                 ])
    //     ];
    // }

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


