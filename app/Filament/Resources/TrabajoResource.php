<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Registro;
use App\Filament\Resources\TrabajoResource\Pages;
use App\Filament\Resources\TrabajoResource\RelationManagers;
use App\Filament\Resources\TrabajoResource\RelationManagers\AnalogosRelationManager;
use App\Filament\Resources\TrabajoResource\RelationManagers\DiscosRelationManager;
use App\Filament\Resources\TrabajoResource\RelationManagers\FresasRelationManager;
use App\Filament\Resources\TrabajoResource\RelationManagers\InterfasesRelationManager;
use App\Filament\Resources\TrabajoResource\RelationManagers\InventarioRelationManager;
use App\Filament\Resources\TrabajoResource\RelationManagers\ResinasRelationManager;
use App\Filament\Resources\TrabajoResource\RelationManagers\TornillosRelationManager;
use App\Jobs\ImprimirTrabajo;
use App\Models\Clinica;
use App\Models\Estado;
use App\Models\Paciente;
use App\Models\Persona;
use App\Models\TipoTrabajo;
use App\Models\Trabajo;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\Action as TableAction;

class TrabajoResource extends Resource
{
    protected static ?string $model = Trabajo::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Work';

    protected static ?string $cluster = Registro::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns([
                        'sm' => 4,
                        'xl' => 4,
                        '2xl' => 4,
                    ])->schema([
                            Fieldset::make('Tipo de Trabajo')
                                ->schema([
                                    Select::make('tipoTrabajos')
                                        ->label('Seleccione los tipos de trabajos')
                                        ->relationship('tipoTrabajo', 'nombre') // nombre del método de la relación y del campo a mostrar
                                        ->multiple()
                                        ->preload() // Opcional: carga todas las opciones al cargar el formulario
                                        ->searchable()
                                        ->createOptionForm([
                                            TextInput::make('nombre')
                                        ]), // Opcional: habilita búsqueda
                                ])->columns(1)->columnSpan(4),
                            // Select::make('paciente_id')
                            //     ->label('Paciente')
                            //     ->options(function () {
                            //         return Paciente::all()->mapWithKeys(function ($paciente) {
                            //             return [$paciente->id => $paciente->persona->identificador . '-' . $paciente->persona->nombre . ' ' . $paciente->persona->apellidos];
                            //         })->toArray();
                            //     })->searchable()->preload()->columnSpan(2),
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
                                    FileUpload::make('foto_boca')
                                        ->disk('public')
                                        ->directory('bocas')
                                        ->image()
                                        ->imageEditor()
                                        ->imageEditorAspectRatios([
                                            '16:9',
                                            '4:3',
                                            '1:1',
                                        ])->columnSpanFull(),
                                    // Radio::make('tipo')
                                    //     ->options([
                                    //         'paciente' => 'Paciente',
                                    //         'doctorImplantes' => 'Doctor Implantes',
                                    //         'doctorOrtodoncia' => 'Doctor Ortodoncia',
                                    //         'doctorFija' => 'Doctor Fija',
                                    //     ])->inline()->columnSpanFull(),
                                    MarkdownEditor::make('nota')->columnSpanFull()
                                ])->createOptionUsing(function (array $data): int {
                                    // Aquí creamos la nueva persona
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
                                    // Retornamos el ID para que Filament lo seleccione automáticamente
                                    return $persona->id;
                                })
                                ->columnSpan(2),
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
                                ])->searchable()
                                ->label("Color")->columnSpan(2),
                            Textarea::make("descripcion")->label("Descripción")->rows(5)->columnSpan(4),
                        ])->columnSpan(2),
                Section::make()
                    ->columns([
                        'sm' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ])->schema([
                            DateTimePicker::make('entrada')->columnSpan(1),
                            DateTimePicker::make('salida')->columnSpan(1),
                            Select::make('estado_id')->relationship('estado', 'nombre')->columnSpan(1),
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
                                ->nullable()->columnSpan(1),
                        ])->columnSpan(1),
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
                })
                    ->columns([
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
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->recordClasses(fn (Model $record) => match ($record->estado->nombre) {
            //     'Diseñado'     => 'bg-orange-100 text-blue-800 font-medium hover:bg-orange-200',
            //     'Fresado'      => 'bg-purple-100 text-purple-800 font-medium hover:bg-purple-200',
            //     'Pendiente'    => 'bg-red-100 text-yellow-800 border-l-4 border-yellow-500 italic hover:bg-red-200',
            //     'Sinterizado'  => 'bg-green-100 text-indigo-800 font-semibold hover:bg-green-200',
            //     'Terminado'    => 'bg-blue-100 text-green-800 font-bold hover:bg-blue-100',
            //     default        => 'bg-white hover:bg-gray-100',
            // })
            ->columns([
                TextColumn::make('id')->label('ID')->formatStateUsing(function ($state) {
                    return "T-" . $state;
                })->searchable(),
                TextColumn::make('tipoTrabajo.nombre')->badge()->color('violet')->searchable()->description(function (Trabajo $record) {
                    if ($record->descripcion) {
                        return $record->descripcion;
                    } else {
                        return "";
                    }

                })->wrap(),
                TextColumn::make('paciente_id')
                    ->label("Paciente-Clinica")->formatStateUsing(function (string $state) {
                        $paciente = Paciente::where('id', $state)->first();
                        $persona = Persona::where('id', $paciente->persona_id)->first();
                        $clinica = Clinica::where('id', $persona->clinica_id)->first();
                        $foto = ($clinica->foto) ? $clinica->foto : "sinfoto.png";
                        return new HtmlString('
                        <div class="flex items-center gap-2">
                            <img src="' . asset("storage/" . $foto) . '" alt="Imagen" width="30" height="30" class="rounded-md shadow-md">
                            <div class="flex flex-col">
                                <span class="font-semibold">' . $clinica->nombre . '</span>
                                <span class="text-sm text-gray-600">' . $persona->nombre . $persona->apellidos . '</span>
                            </div>
                        </div>');
                    })->searchable(
                        query: function (Builder $query, string $search): Builder {
                            $query->orWhereHas('paciente.persona', function (Builder $q) use ($search) {
                                $q->where('nombre', 'like', "%{$search}%")
                                    ->orWhere('apellidos', 'like', "%{$search}%");
                            });
                            return $query;
                        }
                    )->sortable()->wrap(),
                TextColumn::make('estado.nombre')->label('Estado')->badge()->color(function ($state) {
                    if ($state == 'Pendiente') {
                        return "red";
                    } elseif ($state == 'Terminado') {
                        return "sky";
                    } elseif ($state == 'Diseñado') {
                        return "amber";
                    } elseif ($state == 'Fresado') {
                        return "violet";
                    } elseif ($state == 'Sinterizado') {
                        return "green";
                    }
                }),
                TextColumn::make('color_boca')->label('Color')->weight('black'),
                TextColumn::make('piezas')->weight('black')->formatStateUsing(function ($state) {
                    $array = explode(',', $state);
                    return "Total: " . count($array);
                })
                    ->tooltip(function ($state) {
                        return is_array($state) ? implode(', ', $state) : $state;
                    }),
                // TextColumn::make('entrada')->label('Fecha de entrada')->color("warning")->weight('black'),
                TextColumn::make('salida')->description(function ($record) {
                    $entrada = \Carbon\Carbon::parse($record->entrada)->format('d-m-Y');

                    if ($record->estado->nombre === 'Terminado') {
                        return "Entrada: $entrada";
                    }
                    $salida = \Carbon\Carbon::parse($record->salida)->startOfDay();
                    $hoy = \Carbon\Carbon::today();
                    $diasFaltantes = $hoy->diffInDays($salida, false);
                    $mensajeDias = $diasFaltantes > 0
                        ? "Faltan $diasFaltantes días"
                        : ($diasFaltantes === 0
                            ? "Es hoy"
                            : "Debió entregarese hace " . abs($diasFaltantes) . " días");
                    return "Entrada: $entrada | $mensajeDias";
                })->label('Salida')->color("warning")->formatStateUsing(function ($record) {
                    return \Carbon\Carbon::parse($record->salida)->format('d-m-Y');
                })->weight('black')->wrap(),
                ImageColumn::make('qr_token')
                    ->label('Código QR')
                    ->getStateUsing(function ($record) {
                        $qrUrl = 'https://www.ricardoandreu.com/qr/estado/' . $record->qr_token;

                        return 'data:image/svg+xml;base64,' . base64_encode(
                            QrCode::format('svg')->size(250)->generate($qrUrl)
                        );
                    })
                    ->height(60)
                    ->width(60)
                    ->square(),
                // TextColumn::make('files')
                //     ->label('Archivos')
                //     // 1) Recupera el array “crudo” de tu modelo
                //     ->formatStateUsing(function ($state): string {
                //         // 1) Si viene como string JSON, lo decodificamos
                //         if (is_string($state)) {
                //             $arr = json_decode($state, true) ?: [];
                //         }
                //         // 2) Si viene ya como array o colección countable, lo usamos
                //         elseif (is_array($state) || $state instanceof \Countable) {
                //             $arr = $state;
                //         }
                //         // 3) Cualquier otro caso, lo convertimos a array
                //         else {
                //             $arr = (array) $state;
                //         }

                //         $count = count($arr);

                //         return $count . ' ' . Str::plural('archivo', $count);
                //     })->badge()->color('aqua')
                TextColumn::make('files')->label('STL')->getStateUsing(function ($record) {
                    $files = $record->files;
                    return is_array($files) ? count($files) : 0;
                })->badge()->color('violet')
            ])->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('paciente_id')
                    ->options(function () {
                        $personas = Persona::select('id', 'nombre', 'apellidos')
                            ->get()
                            ->mapWithKeys(fn($persona) => [$persona->id => "{$persona->nombre} {$persona->apellidos}"])
                            ->toArray();
                        $pacientes = Paciente::pluck('id', 'persona_id')->toArray();
                        $opciones = [];
                        foreach ($pacientes as $persona_id => $paciente_id) {
                            if (isset($personas[$persona_id])) {
                                $opciones[$paciente_id] = $personas[$persona_id];
                            }
                        }
                        return $opciones;
                    })
            ])
            ->actions(
                [
                    Tables\Actions\EditAction::make()->label('')->iconSize('lg'),
                    Tables\Actions\DeleteAction::make()->label('')->iconSize('lg'),
                    ActionGroup::make([
                        Tables\Actions\Action::make('QR')
                            ->label('IMPRIMIR QR')
                            ->requiresConfirmation()
                            ->iconSize('lg')->icon('heroicon-s-printer')->color('sky')
                            ->modalHeading('¿Deseas imprimir este registro?')
                            ->modalDescription('Se generará un documento PDF de este trabajo')
                            ->url(fn(Model $record) => route('generar.pdf', ['id' => $record->id]))
                            ->openUrlInNewTab(),
                        Tables\Actions\Action::make('DOCUMENT')
                            ->label('IMPRIMIR TRABAJO')
                            ->requiresConfirmation()
                            ->iconSize('lg')->icon('heroicon-s-printer')->color('sky')
                            ->modalHeading('¿Deseas imprimir este registro?')
                            ->modalDescription('Se generará un documento PDF de este trabajo')
                            ->url(fn(Model $record) => route('generar.documento', ['id' => $record->id]))
                            ->openUrlInNewTab(),
                        Tables\Actions\Action::make('0')
                            ->label(function ($record) {
                                return basename($record->files[0]);
                            })
                            ->icon('heroicon-o-eye')
                            ->modalWidth('6xl')
                            ->modalHeading('Vista previa 3D del modelo STL')
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Cerrar')
                            ->modalContent(fn($record) => view('components.stl-viewer', [
                                // Si tu columna ya está casteada a array:
                                'url' => asset(Storage::url($record->files[0])),
                            ]))->extraAttributes([
                                    'class' => 'whitespace-normal break-words justify-center text-center',
                                ])
                            ->visible(function ($record) {
                                $files = $record->files;

                                if (is_string($files)) {
                                    $files = json_decode($files, true) ?: [];
                                } elseif (!is_array($files)) {
                                    $files = (array) $files;
                                }

                                return !empty($files) && isset($files[0]) && !empty($files[0]);
                            }),
                        Tables\Actions\Action::make('1')
                            ->label(function ($record) {
                                return basename($record->files[1]);
                            })
                            ->icon('heroicon-o-eye')
                            ->modalWidth('6xl')
                            ->modalHeading('Vista previa 3D del modelo STL')
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Cerrar')
                            ->modalContent(fn($record) => view('components.stl-viewer', [
                                // Si tu columna ya está casteada a array:
                                'url' => asset(Storage::url($record->files[1])),
                            ]))
                            ->visible(function ($record) {
                                $files = $record->files;

                                if (is_string($files)) {
                                    $files = json_decode($files, true) ?: [];
                                } elseif (!is_array($files)) {
                                    $files = (array) $files;
                                }

                                return !empty($files) && isset($files[1]) && !empty($files[1]);
                            }),
                        Tables\Actions\Action::make('2')
                            ->label(function ($record) {
                                return basename($record->files[2]);
                            })
                            ->icon('heroicon-o-eye')
                            ->modalWidth('6xl')
                            ->modalHeading('Vista previa 3D del modelo STL')
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Cerrar')
                            ->modalContent(fn($record) => view('components.stl-viewer', [
                                // Si tu columna ya está casteada a array:
                                'url' => asset(Storage::url($record->files[2])),
                            ]))
                            ->visible(function ($record) {
                                $files = $record->files;

                                if (is_string($files)) {
                                    $files = json_decode($files, true) ?: [];
                                } elseif (!is_array($files)) {
                                    $files = (array) $files;
                                }

                                return !empty($files) && isset($files[2]) && !empty($files[2]);
                            }),
                        Tables\Actions\Action::make('3')
                            ->label(function ($record) {
                                return basename($record->files[3]);
                            })
                            ->icon('heroicon-o-eye')
                            ->modalWidth('6xl')
                            ->modalHeading('Vista previa 3D del modelo STL')
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Cerrar')
                            ->modalContent(fn($record) => view('components.stl-viewer', [
                                // Si tu columna ya está casteada a array:
                                'url' => asset(Storage::url($record->files[3])),
                            ]))
                            ->visible(function ($record) {
                                $files = $record->files;

                                if (is_string($files)) {
                                    $files = json_decode($files, true) ?: [];
                                } elseif (!is_array($files)) {
                                    $files = (array) $files;
                                }

                                return !empty($files) && isset($files[3]) && !empty($files[3]);
                            }),
                        Tables\Actions\Action::make('4')
                            ->label(function ($record) {
                                return basename($record->files[4]);
                            })
                            ->icon('heroicon-o-eye')
                            ->modalWidth('6xl')
                            ->modalHeading('Vista previa 3D del modelo STL')
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Cerrar')
                            ->modalContent(fn($record) => view('components.stl-viewer', [
                                // Si tu columna ya está casteada a array:
                                'url' => asset(Storage::url($record->files[4])),
                            ]))
                            ->visible(function ($record) {
                                $files = $record->files;

                                if (is_string($files)) {
                                    $files = json_decode($files, true) ?: [];
                                } elseif (!is_array($files)) {
                                    $files = (array) $files;
                                }

                                return !empty($files) && isset($files[4]) && !empty($files[4]);
                            }),
                        Tables\Actions\Action::make('5')
                            ->label(function ($record) {
                                return basename($record->files[5]);
                            })
                            ->icon('heroicon-o-eye')
                            ->modalWidth('6xl')
                            ->modalHeading('Vista previa 3D del modelo STL')
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Cerrar')
                            ->modalContent(fn($record) => view('components.stl-viewer', [
                                // Si tu columna ya está casteada a array:
                                'url' => asset(Storage::url($record->files[5])),
                            ]))
                            ->visible(function ($record) {
                                $files = $record->files;

                                if (is_string($files)) {
                                    $files = json_decode($files, true) ?: [];
                                } elseif (!is_array($files)) {
                                    $files = (array) $files;
                                }

                                return !empty($files) && isset($files[5]) && !empty($files[5]);
                            }),
                        Tables\Actions\Action::make('6')
                            ->label(function ($record) {
                                return basename($record->files[6]);
                            })
                            ->icon('heroicon-o-eye')
                            ->modalWidth('6xl')
                            ->modalHeading('Vista previa 3D del modelo STL')
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Cerrar')
                            ->modalContent(fn($record) => view('components.stl-viewer', [
                                // Si tu columna ya está casteada a array:
                                'url' => asset(Storage::url($record->files[6])),
                            ]))
                            ->visible(function ($record) {
                                $files = $record->files;

                                if (is_string($files)) {
                                    $files = json_decode($files, true) ?: [];
                                } elseif (!is_array($files)) {
                                    $files = (array) $files;
                                }

                                return !empty($files) && isset($files[6]) && !empty($files[6]);
                            }),
                        Tables\Actions\Action::make('7')
                            ->label(function ($record) {
                                return basename($record->files[7]);
                            })
                            ->icon('heroicon-o-eye')
                            ->modalWidth('6xl')
                            ->modalHeading('Vista previa 3D del modelo STL')
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Cerrar')
                            ->modalContent(fn($record) => view('components.stl-viewer', [
                                // Si tu columna ya está casteada a array:
                                'url' => asset(Storage::url($record->files[7])),
                            ]))
                            ->visible(function ($record) {
                                $files = $record->files;

                                if (is_string($files)) {
                                    $files = json_decode($files, true) ?: [];
                                } elseif (!is_array($files)) {
                                    $files = (array) $files;
                                }

                                return !empty($files) && isset($files[7]) && !empty($files[7]);
                            }),
                        Tables\Actions\Action::make('8')
                            ->label(function ($record) {
                                return basename($record->files[8]);
                            })
                            ->icon('heroicon-o-eye')
                            ->modalWidth('6xl')
                            ->modalHeading('Vista previa 3D del modelo STL')
                            ->modalSubmitAction(false)
                            ->modalContent(fn($record) => view('components.stl-viewer', [
                                // Si tu columna ya está casteada a array:
                                'url' => asset(Storage::url($record->files[8])),
                            ]))
                            ->visible(function ($record) {
                                $files = $record->files;

                                if (is_string($files)) {
                                    $files = json_decode($files, true) ?: [];
                                } elseif (!is_array($files)) {
                                    $files = (array) $files;
                                }

                                return !empty($files) && isset($files[8]) && !empty($files[8]);
                            }),
                        Tables\Actions\Action::make('9')
                            ->label(function ($record) {
                                return basename($record->files[9]);
                            })
                            ->icon('heroicon-o-eye')
                            ->modalWidth('6xl')
                            ->modalHeading('Vista previa 3D del modelo STL')
                            ->modalSubmitAction(false)
                            ->modalContent(fn($record) => view('components.stl-viewer', [
                                // Si tu columna ya está casteada a array:
                                'url' => asset(Storage::url($record->files[9])),
                            ]))
                            ->visible(function ($record) {
                                $files = $record->files;

                                if (is_string($files)) {
                                    $files = json_decode($files, true) ?: [];
                                } elseif (!is_array($files)) {
                                    $files = (array) $files;
                                }

                                return !empty($files) && isset($files[9]) && !empty($files[9]);
                            }),
                    ])->dropdownWidth(MaxWidth::TwoExtraLarge),
                ]
            )
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])->deferLoading()
            // ->poll('5s')
        ;
    }

    public static function getRelations(): array
    {
        return [
            DiscosRelationManager::class,
            FresasRelationManager::class,
            ResinasRelationManager::class,
            InterfasesRelationManager::class,
            TornillosRelationManager::class,
            AnalogosRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrabajos::route('/'),
            'create' => Pages\CreateTrabajo::route('/create'),
            'edit' => Pages\EditTrabajo::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return self::$model::count();
        // return static::getModel()::where('estado_id', 1)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return "warning";
    }

    // ESTO LO USO PARA EL WIDGET TABLE DE LOS TRABAJOS EN EL DASHBOARD 
    public static function columnasPersonalizadas(): array
    {
        return [
            TextColumn::make('id')->label('ID')->formatStateUsing(fn($state) => "T-" . $state)->searchable(),
            TextColumn::make('tipoTrabajo.nombre')->badge()->color('violet')->searchable()->description(function (Trabajo $record) {
                if ($record->descripcion) {
                    return $record->descripcion;
                } else {
                    return "";
                }

            })->wrap(),
            TextColumn::make('paciente_id')
                ->label("Paciente-Clinica")->formatStateUsing(function (string $state) {
                    $paciente = Paciente::where('id', $state)->first();
                    $persona = Persona::where('id', $paciente->persona_id)->first();
                    $clinica = Clinica::where('id', $persona->clinica_id)->first();
                    $foto = ($clinica->foto) ? $clinica->foto : "sinfoto.png";
                    return new HtmlString('
                        <div class="flex items-center gap-2">
                            <img src="' . asset("storage/" . $foto) . '" alt="Imagen" width="30" height="30" class="rounded-md shadow-md">
                            <div class="flex flex-col">
                                <span class="font-semibold">' . $clinica->nombre . '</span>
                                <span class="text-sm text-gray-600">' . $persona->nombre . $persona->apellidos . '</span>
                            </div>
                        </div>');
                })->searchable(
                    query: function (Builder $query, string $search): Builder {
                        $query->orWhereHas('paciente.persona', function (Builder $q) use ($search) {
                            $q->where('nombre', 'like', "%{$search}%");
                        });
                        return $query;
                    }
                )->sortable()->wrap(),
            TextColumn::make('estado.nombre')->label('Estado')->badge()->color(function ($state) {
                if ($state == 'Pendiente') {
                    return "red";
                } elseif ($state == 'Terminado') {
                    return "sky";
                } elseif ($state == 'Diseñado') {
                    return "amber";
                } elseif ($state == 'Fresado') {
                    return "violet";
                } elseif ($state == 'Sinterizado') {
                    return "green";
                }
            }),
            TextColumn::make('color_boca')->label('Color')->weight('black'),
            TextColumn::make('piezas')->weight('black')->formatStateUsing(function ($state) {
                $array = explode(',', $state);
                return "Total: " . count($array);
            })
                ->tooltip(function ($state) {
                    return is_array($state) ? implode(', ', $state) : $state;
                }),
            // TextColumn::make('entrada')->label('Fecha de entrada')->color("warning")->weight('black'),
            TextColumn::make('salida')->description(function ($record) {
                $entrada = \Carbon\Carbon::parse($record->entrada)->format('d-m-Y');

                if ($record->estado->nombre === 'Terminado') {
                    return "Entrada: $entrada";
                }
                $salida = \Carbon\Carbon::parse($record->salida)->startOfDay();
                $hoy = \Carbon\Carbon::today();
                $diasFaltantes = $hoy->diffInDays($salida, false);
                $mensajeDias = $diasFaltantes > 0
                    ? "Faltan $diasFaltantes días"
                    : ($diasFaltantes === 0
                        ? "Es hoy"
                        : "Debió entregarese hace " . abs($diasFaltantes) . " días");
                return "Entrada: $entrada | $mensajeDias";
            })->label('Salida')->color("warning")->formatStateUsing(function ($record) {
                return \Carbon\Carbon::parse($record->salida)->format('d-m-Y');
            })->weight('black')->wrap(),
            ImageColumn::make('qr_token')
                ->label('Código QR')
                ->getStateUsing(function ($record) {
                    $qrUrl = 'https://www.ricardoandreu.com/qr/estado/' . $record->qr_token;
                    return 'data:image/svg+xml;base64,' . base64_encode(
                        \QrCode::format('svg')->size(250)->generate($qrUrl)
                    );
                })
                ->height(60)
                ->width(60)
                ->square(),
        ];
    }
}