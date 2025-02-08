<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FresaResource\Pages;
use App\Filament\Resources\FresaResource\RelationManagers;
use App\Models\Fresa;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FresaResource extends Resource
{
    protected static ?string $model = Fresa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Inventario';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tipo')->options([
                    'Mano' => 'Mano',
                    'Fresadora' => 'Fresadora'
                ]),
                TextInput::make('material')->required(),
                TextInput::make('marca')->required(),
                TextInput::make('diametro')->numeric()->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('tipo'),
                TextColumn::make('material'),
                TextColumn::make('marca'),
                TextColumn::make('diametro')
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
            'index' => Pages\ListFresas::route('/'),
            'create' => Pages\CreateFresa::route('/create'),
            'edit' => Pages\EditFresa::route('/{record}/edit'),
        ];
    }
}
