<?php

namespace App\Filament\Clusters\Inventario\Resources;

use App\Filament\Clusters\Inventario;
use App\Filament\Clusters\Inventario\Resources\AnalogoDiametroResource\Pages;
use App\Filament\Clusters\Inventario\Resources\AnalogoDiametroResource\RelationManagers;
use App\Models\Analogo_diametro;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnalogoDiametroResource extends Resource
{
    protected static ?string $model = Analogo_diametro::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    protected static ?string $navigationGroup = 'Ajustes';

    // protected static bool $shouldRegisterNavigation = false;

    protected static ?string $cluster = Inventario::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('valor')->numeric()->required()
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
            'index' => Pages\ListAnalogoDiametros::route('/'),
            'create' => Pages\CreateAnalogoDiametro::route('/create'),
            'edit' => Pages\EditAnalogoDiametro::route('/{record}/edit'),
        ];
    }
}
