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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Actions\CreateAction;

class CalendarWidget extends FullCalendarWidget
{

    public Model|string|null $model = Trabajo::class;

    protected int|string|array $columnSpan = '5';

    public ?string $salidaSeleccionada = null;

    protected function headerActions(): array
    {
        return [
            CreateAction::make()
                ->label('Crear Trabajo ( NO FUNCIONA EL BOTON ¡CUIDADO!) ')
                ->extraAttributes(['class' => 'hidden'])
                ->successNotificationTitle('Trabajo creado correctamente')
                ->mutateFormDataUsing(function (array $data): array {
                    if (isset($data['entrada'], $this->salidaSeleccionada) && $data['entrada'] > $this->salidaSeleccionada) {
                        throw ValidationException::withMessages([
                            'salida' => 'La fecha de salida debe ser posterior a la entrada.',
                        ]);
                    }
                    $piezas = collect([
                        $data['cuadrante1'] ?? [],
                        $data['cuadrante2'] ?? [],
                        $data['cuadrante3'] ?? [],
                        $data['cuadrante4'] ?? [],
                    ])->flatten()->sort()->values()->all(); // Ordena y limpia
                    return [
                        'paciente_id' => $data['paciente_id'],
                        'descripcion' => $data['descripcion'] ?? '',
                        'entrada' => $data['entrada'] ?? now(),
                        'salida' => $this->salidaSeleccionada ?? now(),
                        'color_boca' => $data['color_boca'] ?? null,
                        'estado_id' => $data['estado_id'] ?? null,
                        'piezas' => $piezas,

                        // NOTA: tipoTrabajo NO se mete aquí directamente, Filament la sincroniza aparte
                    ];
                })
                ->form([
                    Grid::make(2) // 👈 2 columnas
                        ->schema([
                            Select::make('paciente_id')
                                ->label('Paciente')
                                ->relationship('paciente', 'id')
                                ->getOptionLabelFromRecordUsing(function (Model $record) {
                                    return $record->persona->identificador . '-' . $record->persona->nombre . ' ' . $record->persona->apellidos;
                                })
                                ->searchable()
                                ->preload()
                                ->createOptionForm([
                                    TextInput::make('identificador')->required(),
                                    TextInput::make('nombre')->label("Nombre"),
                                    TextInput::make('apellidos')->label("Apellidos"),
                                    TextInput::make('telefono')->label("Número de teléfono"),
                                    Select::make('clinica_id') // usa el nombre real de la columna
                                        ->label('Clínica')
                                        ->options(Clinica::all()->pluck('nombre', 'id')) // [id => nombre]
                                        ->required(),
                                    FileUpload::make('files')
                                        ->helpertext("Sube aquí uno o varios archivos STL")
                                        ->directory('trabajos')
                                        ->multiple()
                                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                            $fecha = now()->format('Y-m-d');
                                            $nombre = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                                            $extension = $file->getClientOriginalExtension();
                                        
                                            return "{$fecha}-{$nombre}.{$extension}";
                                        })
                                        ->maxSize(40960)
                                        ->acceptedFileTypes([
                                            'application/sla',
                                            'application/vnd.ms-pki.stl',
                                            'model/stl',
                                        ])
                                        ->openable()
                                        ->downloadable()
                                        ->nullable()->columnSpanFull(),
                                    // Radio::make('tipo')
                                    //     ->options([
                                    //         'paciente' => 'Paciente',
                                    //         'doctorImplantes' => 'Doctor Implantes',
                                    //         'doctorOrtodoncia' => 'Doctor Ortodoncia',
                                    //         'doctorFija' => 'Doctor Fija',
                                    //     ])->inline()->columnSpanFull(),
                                    MarkdownEditor::make('nota')->columnSpanFull()
                                ])->createOptionUsing(function (array $data): int {
                                    $persona = \App\Models\Persona::create([
                                        'identificador' => $data['identificador'],
                                        'clinica_id' => $data['clinica_id'],
                                        'nombre' => $data['nombre'],
                                        'apellidos' => $data['apellidos'],
                                        'telefono' => $data['telefono'],
                                        'tipo' => 'paciente',
                                        'nota' => $data['nota'],
                                        'foto_boca' => $data['foto_boca'],
                                    ]);
                                    $persona->save();
                                    return $persona->id;
                                })->columnSpan(1),
                            Select::make('tipoTrabajo')
                                ->label('Seleccione los tipos de trabajos')
                                ->relationship('tipoTrabajo', 'nombre')
                                ->multiple()
                                ->preload()
                                ->searchable()
                                ->createOptionForm([
                                    TextInput::make('nombre')->required(),
                                    TextInput::make('precio')->numeric()->required()
                                ]), // Opcional: habilita búsqueda
                            Textarea::make('descripcion')
                                ->label('Descripción')->columnSpan(2),
                            Select::make('color_boca')
                                ->options([
                                    "A1" => "A1",
                                    "A2" => "A2",
                                    "A3" => "A3",
                                    "A3.5" => "A3.5",
                                    "A4" => "A4",
                                    "B1" => "B1",
                                    "B2" => "B2",
                                    "B3" => "B3",
                                    "B4" => "B4",
                                    "C1" => "C1",
                                    "C2" => "C2",
                                    "C3" => "C3",
                                    "C4" => "C4",
                                    "D1" => "D1",
                                    "D2" => "D2",
                                    "D3" => "D3",
                                    "D4" => "D4",
                                    "OM1" => "OM1",
                                    "OM2" => "OM2",
                                    "OM3" => "OM3",
                                ])->searchable()->label("Color")->columnSpan(1),
                            Select::make('estado_id')->relationship('estado', 'nombre')->columnSpan(1),
                            FileUpload::make('files')->label('Archivos')
                                ->helpertext("Sube aquí uno o varios archivos STL")
                                ->directory('trabajos')
                                ->multiple()
                                ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                    $fecha = now()->format('Y-m-d');
                                    $nombre = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                                    $extension = $file->getClientOriginalExtension();
                            
                                    return "{$fecha}-{$nombre}.{$extension}";
                                })
                                ->openable()
                                ->downloadable()
                                ->nullable()->columnSpanFull(),
                            Section::make("Piezas")->description(function ($state) {
                                $seleccionados = collect([
                                    $state['cuadrante1'] ?? [],
                                    $state['cuadrante2'] ?? [],
                                    $state['cuadrante3'] ?? [],
                                    $state['cuadrante4'] ?? [],
                                ])->flatten();
                                if ($seleccionados->isEmpty()) {
                                    return "Selecciona los dientes de cada cuadrante para este trabajo.";
                                }
                                return "Dientes seleccionados: " . $seleccionados->sort()->join(', ');
                            })->columns([
                                        'sm' => 2,
                                        'xl' => 2,
                                        '2xl' => 2,
                                    ])->schema([
                                        Section::make([
                                            CheckboxList::make('cuadrante1')->label(" ")
                                                ->options(['18' => '18', '17' => '17', '16' => '16', '15' => '15', '14' => '14', '13' => '13', '12' => '12', '11' => '11',])
                                                ->columns(8)
                                                ->bulkToggleable()->live()
                                        ])->columnSpan(1),
                                        Section::make([
                                            CheckboxList::make('cuadrante2')->label(" ")
                                                ->options(['21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28',])
                                                ->columns(8)
                                                ->bulkToggleable()->live()
                                        ])->columnSpan(1),
                                        Section::make([
                                            CheckboxList::make('cuadrante4')->label(" ")
                                                ->options(['48' => '48', '47' => '47', '46' => '46', '45' => '45', '44' => '44', '43' => '43', '42' => '42', '41' => '41',])
                                                ->columns(8)
                                                ->bulkToggleable()->live()
                                        ])->columnSpan(1),
                                        Section::make([
                                            CheckboxList::make('cuadrante3')->label(" ")
                                                ->options(['31' => '31', '32' => '32', '33' => '33', '34' => '34', '35' => '35', '36' => '36', '37' => '37', '38' => '38',])
                                                ->columns(8)
                                                ->bulkToggleable()->live()
                                        ])->columnSpan(1),
                                    ])
                        ])
                ])
                ->mountUsing(function (Form $form, array $arguments) {
                    $this->salidaSeleccionada = $arguments['start'] ?? now();
                    $form->fill([
                        'entrada' => now(),
                    ]);
                })->slideOver()->modalFooterActionsAlignment('center')->modalHeading(function (array $arguments) {
                    return 'NUEVO TRABAJO PARA EL DIA ' . \Carbon\Carbon::parse($arguments['start'])->format('d-m-Y');
                })->modalWidth(MaxWidth::MaxContent)
        ];
    }
    public function fetchEvents(array $fetchInfo): array
    {

        $estadoColors = [
            'Pendiente' => 'rgba(239, 68, 68, 0.2)',     // red-500 transparente
            'Terminado' => 'rgba(14, 165, 233, 0.2)',    // sky-500 transparente
            'Diseñado' => 'rgba(245, 158, 11, 0.2)',     // amber-500 transparente
            'Fresado' => 'rgba(139, 92, 246, 0.2)',      // violet-500 transparente
            'Sinterizado' => 'rgba(34, 197, 94, 0.2)',   // green-500 transparente
        ];

        return Trabajo::query()
            ->where('salida', '>=', $fetchInfo['start'])
            ->where('salida', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (Trabajo $event) use ($estadoColors) {
                $paciente = Paciente::where('id', $event->paciente_id)->first();
                $persona = Persona::where('id', $paciente->persona_id)->first();
                $clinica = Clinica::where('id', $persona->clinica_id)->first();
                $descripcion = $event->descripcion ?? 'No hay descripción';

                $estadoNombre = $event->estado?->nombre;
                return [
                    'id' => $event->id,
                    'title' => $persona->nombre . " $persona->apellidos" . " - " . $clinica->nombre,
                    'start' => $event->salida,
                    'end' => $event->salida,
                    'url' => TrabajoResource::getUrl(name: 'edit', parameters: ['record' => $event]),
                    'shouldOpenUrlInNewTab' => true,
                    'backgroundColor' => $estadoColors[$estadoNombre] ?? '#cccccc',
                    'textColor' => '#000000',
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
            'locale' => 'es',
            'dayCellClassNames' => ['day-hover-effect'],
            'editable' => false, // 🔵 sí quiero mover
            'durationEditable' => false, // 🔴 no quiero estirar
            // 'dayMaxEventRows' => 3, // permitir ver hasta 3 eventos
            'fixedWeekCount' => false, // 🔥 no forzar siempre 6 filas (opcional)
            'aspectRatio' => 1.8, // 🔥 hace más grande verticalmente
            'event',
            'views' => [
            'dayGridWeek' => [
                'dayMaxEventRows' => 8, // 👈 mostrar hasta 8 eventos por día en vista semana
            ],
            'dayGridMonth' => [
                'dayMaxEventRows' => 3, // 👈 en vista mensual se mantienen los 3
            ],
        ],
        ];
    }
}


