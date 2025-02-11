<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClinicaResource\Pages;
use App\Filament\Resources\ClinicaResource\RelationManagers;
use App\Filament\Resources\ClinicaResource\RelationManagers\PersonaRelationManager;
use App\Models\Clinica;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClinicaResource extends Resource
{
    protected static ?string $model = Clinica::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Work';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')->label("Nombre"),
                TextInput::make('direccion')->label("Dirección"),
                TextInput::make('telefono')->label("Número de teléfono"),
                Textarea::make("descripcion")->label("Descripción")
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('nombre')->badge()->color("success"),
                TextColumn::make('direccion'),
                TextColumn::make('telefono'),
                TextColumn::make('descripcion')->limit(30),
                TextColumn::make('created_at')->color("warning"),
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
           PersonaRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClinicas::route('/'),
            'create' => Pages\CreateClinica::route('/create'),
            'edit' => Pages\EditClinica::route('/{record}/edit'),
        ];
    }
}
