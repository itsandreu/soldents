<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Inventario;
use App\Filament\Clusters\Registro;
use App\Filament\Resources\TipoTrabajoResource\Pages;
use App\Filament\Resources\TipoTrabajoResource\RelationManagers;
use App\Models\TipoTrabajo;
use App\Models\Trabajo;
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

class TipoTrabajoResource extends Resource
{
    protected static ?string $model = TipoTrabajo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $navigationGroup = 'Configuración';

    protected static ?string $cluster = Registro::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')->required(),
                TextInput::make('precio')->required()->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
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
            'index' => Pages\ListTipoTrabajos::route('/'),
            'create' => Pages\CreateTipoTrabajo::route('/create'),
            'edit' => Pages\EditTipoTrabajo::route('/{record}/edit'),
        ];
    }
}
