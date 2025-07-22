<?php

namespace App\Filament\Clusters\Inventario\Resources;

use App\Filament\Clusters\Inventario;
use App\Filament\Clusters\Inventario\Resources\InterfaseAlturaGResource\Pages;
use App\Filament\Clusters\Inventario\Resources\InterfaseAlturaGResource\RelationManagers;
use App\Models\Interfase_altura_g;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InterfaseAlturaGResource extends Resource
{
    protected static ?string $model = Interfase_altura_g::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    protected static ?string $navigationGroup = 'Ajustes';

    // protected static bool $shouldRegisterNavigation = false;

    protected static ?string $cluster = Inventario::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('valor')->required()->numeric()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('valor')
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
            'index' => Pages\ListInterfaseAlturaGS::route('/'),
            'create' => Pages\CreateInterfaseAlturaG::route('/create'),
            'edit' => Pages\EditInterfaseAlturaG::route('/{record}/edit'),
        ];
    }
}
