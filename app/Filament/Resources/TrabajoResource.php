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
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrabajoResource extends Resource
{
    protected static ?string $model = Trabajo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                    Select::make('tipo_trabajo_id')->options(TipoTrabajo::all()->pluck('nombre','id')),
                    Textarea::make("descripcion")->label("Descripción")->columnSpan(2),
                    Select::make('paciente_id')
                    ->label('Paciente')
                    ->options(function () {
                        return Paciente::all()->mapWithKeys(function ($paciente) {
                            return [$paciente->id => $paciente->persona->nombre];
                        })->toArray();
                    })->searchable()->columnSpan(1),
                    Select::make('estado_id')->relationship('estado','nombre'),
                    Select::make('color_boca')
                    ->options(["A1" =>"A1","A2" =>"A2","A3" =>"A3","A3.5" =>"A3.5","A4" =>"A4",
                                "B1" =>"B1","B2" =>"B2","B3" =>"B3","B4" =>"B4","C1" =>"C1","C2" =>"C2",
                                "C3" =>"C3","C4" =>"C4","D1" =>"D1","D2" =>"D2","D3" =>"D3","D4" =>"D4",
                                "OM1" =>"OM1","OM2" =>"OM2","OM3" =>"OM3",
                    ])->searchable()
                    ->label("Color")->columnSpan(2),
                ]),
                Section::make()
                ->columns([
                    'sm' => 4,
                    'xl' => 4,
                    '2xl' => 4,
                ])->schema([
                    DateTimePicker::make('entrada'),
                    DateTimePicker::make('salida')
                ])
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tipoTrabajo.nombre')->badge()->color('success'),
                TextColumn::make('descripcion')->label('Descripción'),
                TextColumn::make('paciente_id')->label("Paciente-Clinica")->formatStateUsing(function (String $state){
                    $paciente = Paciente::where('id',$state)->first();
                    $persona = Persona::where('id',$paciente->persona_id)->first();
                    $clinica = Clinica::where('id', $persona->clinica_id)->first();
                    return $persona->nombre. " - " . $clinica->nombre;
                }),
                TextColumn::make('estado.nombre')->label('Estado')->badge()->color(function($state){
                    if ($state == 'Pendiente') {
                        return "red";
                    }elseif ($state == 'Terminado') {
                        return "sky";
                    }elseif ($state == 'Diseñado') {
                        return "amber";
                    }elseif ($state == 'Fresado') {
                        return "violet";
                    }
                }),
                TextColumn::make('color_boca')->label('Color'),
                TextColumn::make('entrada')->label('Fecha de entrada')->color("warning"),
                TextColumn::make('salida')->label('Fecha de salida')->color("warning"),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
}
