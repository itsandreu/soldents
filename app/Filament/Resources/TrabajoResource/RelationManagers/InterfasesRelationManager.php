<?php

namespace App\Filament\Resources\TrabajoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InterfasesRelationManager extends RelationManager
{
    protected static string $relationship = 'interfases';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('tipo')
                //     ->required()
                //     ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tipo')
            ->columns([
                    TextColumn::make('marca.nombre')->sortable(),
                    TextColumn::make('tipo.nombre')->sortable(),
                    TextColumn::make('diametro.valor')->sortable(),
                    TextColumn::make('alturaH.nombre')->sortable(),
                    TextColumn::make('alturaG.valor')->sortable(),
                    TextColumn::make('referencia')->sortable()->badge()->color('violet'),
                    TextColumn::make('unidades')->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                ->label('Añadir Interfase')
                ->preloadRecordSelect()
                ->recordTitle(fn($record) => 
                    "{$record->marca->nombre} - {$record->tipo->nombre} 
                    ( Diametro: {$record->diametro->valor} - AlturaH: {$record->alturaH->nombre} 
                    - AlturaG: {$record->alturaG->valor} - Rotación: {$record->rotacion} - Ref: {$record->referencia})")
                ->recordSelectOptionsQuery(
                    fn(Builder $query) =>
                    $query->where('status', 'En uso')
                ),
            ])
            ->actions([
                DetachAction::make()->label('Quitar Interfase')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
