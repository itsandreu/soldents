<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrabajoResource\Pages;
use App\Filament\Resources\TrabajoResource\RelationManagers;
use App\Filament\Resources\TrabajoResource\RelationManagers\InventarioRelationManager;
use App\Models\Clinica;
use App\Models\Paciente;
use App\Models\Persona;
use App\Models\Trabajo;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
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
                TextInput::make("nombre")->label("Nómbre del Trabajo"),
                Textarea::make("descripcion")->label("Descripción"),
                Select::make('paciente_id')->relationship("paciente",'id')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("nombre")->label('Trabajo'),
                TextColumn::make('descripcion')->label('Descripción'),
                TextColumn::make('paciente_id')->label("Paciente-Clinica")->formatStateUsing(function (String $state){
                    $paciente = Paciente::where('id',$state)->first();
                    $persona = Persona::where('id',$paciente->persona_id)->first();
                    $clinica = Clinica::where('id', $persona->clinica_id)->first();
                    return $persona->nombre. " - " . $clinica->nombre;
                }),
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
