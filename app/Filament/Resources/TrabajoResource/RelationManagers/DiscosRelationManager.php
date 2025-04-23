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

class DiscosRelationManager extends RelationManager
{
    protected static string $relationship = 'discos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('material')
                //     ->required()
                //     ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('material')
            ->columns([
                TextColumn::make('material')->sortable(),
                TextColumn::make('marca')->sortable(),
                TextColumn::make('color')->sortable(),
                TextColumn::make('translucidez')->sortable(),
                TextColumn::make('dimensiones')->sortable(),
                TextColumn::make('reduccion')->sortable(),
                TextColumn::make('lote')->sortable()->badge()->color('violet'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Añadir Disco')
                    ->preloadRecordSelect()
                    ->recordTitle(fn($record) => "{$record->material} - {$record->marca} ({$record->color} - {$record->translucidez} - {$record->dimensiones} - {$record->reduccion} - {$record->lote})")
                    ->recordSelectOptionsQuery(
                        fn(Builder $query) =>
                        $query->where('status', 'En uso')
                    ),
            ])
            ->actions([
                DetachAction::make()->label('Quitar Disco')->requiresConfirmation()->modalHeading('¿Estas seguro que quieres quitar el disco de este trabajo?')->modalDescription('Podría verse afectada la integridad de los datos.')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
