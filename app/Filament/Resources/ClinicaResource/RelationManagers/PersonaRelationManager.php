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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;

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
                ])
                ->inline()
                ->columnSpan(1)
                ->required()
                ->live() 
                ->afterStateUpdated(function ($state, $set, $get, $record) {

                    $set('foto_boca', null);
            
                    if ($record) {
                        \Filament\Notifications\Notification::make()
                            ->title('¡Atención!')
                            ->body('Este usuario PUEDE tener trabajos asociados. Si cambias su tipo, se ELIMINARAN todos los trabajos.')
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }),
            
                FileUpload::make('foto_boca')
                ->disk('public')
                ->directory('bocas')
                ->image()
                ->imageEditor()
                ->imageEditorAspectRatios([
                    '16:9',
                    '4:3',
                    '1:1',
                ])->columnSpanFull()
                ->visible(fn($get) => $get('tipo') === 'paciente'),
                MarkdownEditor::make('nota')->columnSpanFull(), 
                TextInput::make('clinica_id')->default(function(){
                    return $this->getOwnerRecord()->id;
                })->disabled()->hidden(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function (Builder $query) {
                
                $clinicaId = $this->getOwnerRecord()->id;

                // Modificar la consulta para filtrar por el clinica_id
                $query->where('clinica_id', $clinicaId);
                
            })->groups([
                'tipo',
            ])->defaultGroup('tipo')->modelLabel('Persona')
            ->recordTitleAttribute('nombre')
            ->columns([
                Stack::make([
                    Tables\Columns\TextColumn::make('nombre')->alignCenter()->weight('black')->searchable(),
                    Tables\Columns\TextColumn::make('apellidos')->alignCenter()->weight('black')->label("Apellidos"),
                    Tables\Columns\TextColumn::make('telefono')->alignCenter()->weight('black')->label('Telefono'),
                    // Tables\Columns\TextColumn::make('tipo')->label('Tipo')->searchable(),
                    ImageColumn::make('foto_boca')->alignCenter()->circular()->default(asset("storage/sinfoto.png")),
                ])
            ])->contentGrid([
                'md' => 3,
                'xl' => 5,
            ])
            ->filters([
                
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Agregar Persona'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([

            ]);
    }
}
