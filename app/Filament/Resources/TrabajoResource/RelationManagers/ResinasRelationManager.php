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

class ResinasRelationManager extends RelationManager
{
    protected static string $relationship = 'resinas';

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
                TextColumn::make('tipo'),
                TextColumn::make('marca'),
                TextColumn::make('litros'),
                TextColumn::make('lote')->badge()->color('violet'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Añadir Resina')
                    ->preloadRecordSelect()
                    ->recordTitle(fn($record) => "{$record->tipo} - {$record->marca} ({$record->litros} - {$record->lote})")
                    ->recordSelectOptionsQuery(
                        fn(Builder $query) =>
                        $query->where('status', 'En uso')
                    ),
            ])
            ->actions([
                DetachAction::make()->label('Quitar Resina')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
