<?php

namespace App\Filament\Clusters\Inventario\Resources;

use App\Filament\Clusters\Inventario;
use App\Filament\Clusters\Inventario\Resources\InterfaseMarcaResource\Pages;
use App\Filament\Clusters\Inventario\Resources\InterfaseMarcaResource\RelationManagers;
use App\Models\Interfase_marca;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InterfaseMarcaResource extends Resource
{
    protected static ?string $model = Interfase_marca::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    protected static ?string $navigationGroup = 'Ajustes';

    // protected static bool $shouldRegisterNavigation = false;

    protected static ?string $cluster = Inventario::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
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
            'index' => Pages\ListInterfaseMarcas::route('/'),
            'create' => Pages\CreateInterfaseMarca::route('/create'),
            'edit' => Pages\EditInterfaseMarca::route('/{record}/edit'),
        ];
    }
}
