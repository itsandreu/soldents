<?php

namespace App\Filament\Resources\TrabajoResource\RelationManagers;

use App\Models\Inventario;
use App\Models\Trabajo;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventarioRelationManager extends RelationManager
{
    protected static string $relationship = 'inventario';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                TextInput::make("descripcion")->label("Descripción"),
                TextInput::make("cantidad")->numeric()->label("Cantidad (unidades)"),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table->description("Inventario utilizado en este trabajo")
            ->columns([
                Tables\Columns\TextColumn::make('nombre'),
                Tables\Columns\TextColumn::make('descripcion'),
                Tables\Columns\TextColumn::make('cantidad'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()->recordTitleAttribute('nombre')->label("Añadir")->preloadRecordSelect()
                                                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                                                    $action->getRecordSelect()->preload(),

                ]),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
