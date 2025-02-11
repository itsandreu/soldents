<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\TrabajoResource;
use App\Models\Trabajo;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = Trabajo::class;

    public function fetchEvents(array $fetchInfo): array
    {
        // You can use $fetchInfo to filter events by date.
        // This method should return an array of event-like objects. See: https://github.com/saade/filament-fullcalendar/blob/3.x/#returning-events
        // You can also return an array of EventData objects. See: https://github.com/saade/filament-fullcalendar/blob/3.x/#the-eventdata-class
        return Trabajo::query()
        ->where('entrada', '>=', $fetchInfo['start'])
        ->where('salida', '<=', $fetchInfo['end'])
        ->get()
        ->map(
            fn (Trabajo $event) => [
                'title' => $event->id,
                'start' => $event->starts_at,
                'end' => $event->ends_at,
                'url' => TrabajoResource::getUrl(name: 'edit', parameters: ['record' => $event]),
                'shouldOpenUrlInNewTab' => true
            ]
        )
        ->all();
    }

    public function config(): array
    {
        return [
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'dayGridWeek,dayGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
        ];
    }
}
