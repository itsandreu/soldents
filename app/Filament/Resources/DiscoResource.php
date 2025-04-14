<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Inventario;
use App\Filament\Resources\DiscoResource\Pages;
use App\Filament\Resources\DiscoResource\RelationManagers;
use App\Models\Disco;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiscoResource extends Resource
{
    protected static ?string $model = Disco::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $navigationGroup = 'Recursos';

    protected static ?string $cluster = Inventario::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('material')->required(),
                TextInput::make('marca')->required(),
                TextInput::make('color')->required(),
                TextInput::make('translucidez')->required(),
                TextInput::make('dimensiones')->required(),
                TextInput::make('reduccion')->numeric()->required(),
                TextInput::make('lote')->required()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('material'),
                TextColumn::make('marca'),
                TextColumn::make('color'),
                TextColumn::make('translucidez'),
                TextColumn::make('dimensiones'),
                TextColumn::make('reduccion'),
                TextColumn::make('lote'),

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
            'index' => Pages\ListDiscos::route('/'),
            'create' => Pages\CreateDisco::route('/create'),
            'edit' => Pages\EditDisco::route('/{record}/edit'),
        ];
    }
}
