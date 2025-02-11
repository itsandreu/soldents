<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrabajoResource\Pages;
use App\Filament\Resources\TrabajoResource\RelationManagers;
use App\Filament\Resources\TrabajoResource\RelationManagers\InventarioRelationManager;
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
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class TrabajoResource extends Resource
{
    protected static ?string $model = Trabajo::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Work';



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
                                ->options(['31' => '31','32' => '32','33' => '33','34' => '34','35' => '35','36' => '36','37' => '37','38' => '38',])
                                ->columns(8)
                                ->bulkToggleable()
                        ])->columnSpan(1),
                    ])
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tipoTrabajo.nombre')->badge()->color('success')->searchable(),
                TextColumn::make('descripcion')->wrap()->label('Descripción'),
                TextColumn::make('paciente_id')
                    ->label("Paciente-Clinica")->formatStateUsing(function (String $state) {
                    $paciente = Paciente::where('id', $state)->first();
                    $persona = Persona::where('id', $paciente->persona_id)->first();
                    $clinica = Clinica::where('id', $persona->clinica_id)->first();
                    return $persona->nombre . " - " . $clinica->nombre;
                })->searchable(),
                TextColumn::make('estado.nombre')->label('Estado')->badge()->color(function ($state) {
                    if ($state == 'Pendiente') {
                        return "red";
                    } elseif ($state == 'Terminado') {
                        return "sky";
                    } elseif ($state == 'Diseñado') {
                        return "amber";
                    } elseif ($state == 'Fresado') {
                        return "violet";
                    }elseif ($state == 'Sinterizado') {
                        return "green";
                    }
                }),
                TextColumn::make('color_boca')->label('Color'),
                TextColumn::make('piezas')->formatStateUsing(function ($state) {$array = explode(',', $state);return "Total: " . count($array);})
                ->tooltip(function ($state) {
                    return is_array($state) ? implode(', ', $state) : $state;
                }),
                TextColumn::make('entrada')->label('Fecha de entrada')->color("warning"),
                TextColumn::make('salida')->label('Fecha de salida')->color("warning"),
            ])
            ->filters([
                SelectFilter::make('paciente_id')
                        ->options(function (){
                            $personas = Persona::pluck('nombre','id')->toArray();
                            $pacientes = Paciente::pluck('id','persona_id')->toArray();
                            $opciones = [];
                            foreach ($pacientes as $nombre_persona_id => $paciente_id) {
                                if(isset($personas[$nombre_persona_id])){
                                    $opciones[$paciente_id] = $personas[$nombre_persona_id];
                                }
                            }
                            return $opciones;
                        })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            InventarioRelationManager::class
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
        return static::getModel()::where('estado_id',1)->count();
    }

    public static function getNavigationBadgeColor(): ?string
{
     return "warning";
}
}
