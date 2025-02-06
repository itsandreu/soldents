<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PersonaResource\Pages;
use App\Filament\Resources\PersonaResource\RelationManagers;
use App\Models\Persona;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PersonaResource extends Resource
{
    protected static ?string $model = Persona::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Work';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')->label("Nombre"),
                TextInput::make('apellidos')->label("Dirección"),
                TextInput::make('telefono')->label("Número de teléfono"),
                Select::make('clinica')->relationship('clinica','nombre')->required(),
                Radio::make('tipo')
                ->options([
                    'paciente' => 'Paciente',
                    'doctorImplantes' => 'Doctor Implantes',
                    'doctorOrtodoncia' => 'Doctor Ortodoncia',
                    'doctorFijq' => 'Doctor Fija',
                ])->inline()->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('nombre'),
                TextColumn::make('apellidos'),
                TextColumn::make('telefono'),
                TextColumn::make('clinica.nombre')->limit(30),
                TextColumn::make('tipo')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPersonas::route('/'),
            'create' => Pages\CreatePersona::route('/create'),
            'edit' => Pages\EditPersona::route('/{record}/edit'),
        ];
    }
}
