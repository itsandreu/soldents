<?php

namespace App\Filament\Clusters\Inventario\Resources;

use App\Filament\Clusters\Inventario;
use App\Filament\Clusters\Inventario\Resources\InterfaseAlturaHResource\Pages;
use App\Filament\Clusters\Inventario\Resources\InterfaseAlturaHResource\RelationManagers;
use App\Models\Interfase_altura_h;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InterfaseAlturaHResource extends Resource
{
    protected static ?string $model = Interfase_altura_h::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Ajustes';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $cluster = Inventario::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')->required()->numeric()->label('valor')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')->label('valor')
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
            'index' => Pages\ListInterfaseAlturaHS::route('/'),
            'create' => Pages\CreateInterfaseAlturaH::route('/create'),
            'edit' => Pages\EditInterfaseAlturaH::route('/{record}/edit'),
        ];
    }
}
