<?php

namespace App\Filament\Resources\ClinicaResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables\Actions\CreateAction;
use App\Models\Persona;

class PersonaRelationManager extends RelationManager
{
    protected static string $relationship = 'Persona';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')->required()->label("Nombre"),
                TextInput::make('apellidos')->required()->label("Apellidos"),
                TextInput::make('telefono')->required()->label("Número de teléfono"),
                // Select::make('clinica')->relationship('clinica','nombre')->required(),
                Radio::make('tipo')
                ->options([
                    'paciente' => 'Paciente',
                    'doctorImplantes' => 'Doctor Implantes',
                    'doctorOrtodoncia' => 'Doctor Ortodoncia',
                    'doctorFija' => 'Doctor Fija',
                ])->inline()->columnSpanFull()->required(),
                MarkdownEditor::make('nota')->columnSpanFull(), 
                TextInput::make('clinica_id')->default(function(){
                    return $this->getOwnerRecord()->id;
                })->disabled()->hidden()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function (Builder $query) {
                
                $clinicaId = $this->getOwnerRecord()->id;

                // Modificar la consulta para filtrar por el clinica_id
                $query->where('clinica_id', $clinicaId);
                
            })
            ->recordTitleAttribute('nombre')
            ->columns([
                Tables\Columns\TextColumn::make('nombre')->searchable(),
                Tables\Columns\TextColumn::make('apellidos')->label("Apellidos"),
                Tables\Columns\TextColumn::make('telefono')->label('Telefono'),
                Tables\Columns\TextColumn::make('tipo')->label('Tipo')->searchable(),
            ])
            ->filters([
                
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
