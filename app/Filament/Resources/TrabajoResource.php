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
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;


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
                        'sm' => 3,
                        'xl' => 3,
                        '2xl' => 3,
                    ])->schema([
                        Select::make('tipo_trabajo_id')->options(TipoTrabajo::all()->pluck('nombre', 'id'))->searchable()->preload(),
                        Select::make('paciente_id')
                            ->label('Paciente')
                            ->options(function () {
                                return Paciente::all()->mapWithKeys(function ($paciente) {
                                    return [$paciente->id => $paciente->persona->nombre];
                                })->toArray();
                            })->searchable()->columnSpan(1),
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
                            ->label("Color")->columnSpan(1),
                        Textarea::make("descripcion")->label("Descripción")->rows(5)->columnSpan(3),
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
                    ])->columnSpan(1),
                Section::make("Piezas")->description("Selecciona los dientes de cada cuadrante para este trabajo.")
                    ->columns([
                        'sm' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])->schema([
                        Section::make([
                            CheckboxList::make('cuadrante1')->label(" ")
                                ->options(['18' => '18', '17' => '17', '16' => '16', '15' => '15', '14' => '14', '13' => '13', '12' => '12', '11' => '11',])
                                ->columns(8)
                                ->bulkToggleable()
                        ])->columnSpan(1),
                        Section::make([
                            CheckboxList::make('cuadrante2')->label(" ")
                                ->options(['21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28',])
                                ->columns(8)
                                ->bulkToggleable()
                        ])->columnSpan(1),
                        Section::make([
                            CheckboxList::make('cuadrante4')->label(" ")
                                ->options(['48' => '48', '47' => '47', '46' => '46', '45' => '45', '44' => '44', '43' => '43', '42' => '42', '41' => '41',])
                                ->columns(8)
                                ->bulkToggleable()
                        ])->columnSpan(1),
                        Section::make([
                            CheckboxList::make('cuadrante3')->label(" ")
                                ->options(['31' => '31', '32' => '32', '33' => '33', '34' => '34', '35' => '35', '36' => '36', '37' => '37', '38' => '38',])
                                ->columns(8)
                                ->bulkToggleable(),
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
                }),
                TextColumn::make('tipoTrabajo.nombre')->badge()->color('black')->searchable()->description(function (Trabajo $record) {
                    return $record->descripcion;
                })->wrap(),
                TextColumn::make('paciente_id')
                    ->label("Paciente-Clinica")->formatStateUsing(function (String $state) {
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
                    })->searchable()->sortable()->wrap(),
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
                    $entrada = \Carbon\Carbon::parse($record->entrada)->format('Y-m-d');
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
                })->label('Salida')->color("warning")->weight('black')->wrap(),
            ])
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
            ->actions([
                Tables\Actions\EditAction::make()->label('')->iconSize('lg'),
                Tables\Actions\DeleteAction::make()->label('')->iconSize('lg'),
                Tables\Actions\Action::make('Print')
                    ->requiresConfirmation()->label('')
                    ->iconSize('lg')->icon('heroicon-s-printer')->color('sky')
                    ->modalHeading('¿Deseas imprimir este registro?')
                    ->modalDescription('Se generará un documento PDF de este trabajo')
                    ->url(fn (Model $record) => route('generar.pdf', ['id' => $record->id]))
                     ->openUrlInNewTab()
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
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
}
