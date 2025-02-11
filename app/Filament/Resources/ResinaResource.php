<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResinaResource\Pages;
use App\Filament\Resources\ResinaResource\RelationManagers;
use App\Models\Resina;
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

class ResinaResource extends Resource
{
    protected static ?string $model = Resina::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Inventario';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tipo')
                    ->options([
                        'Modelos' => 'Modelos',
                        'Férulas' => 'Férulas',
                        'Encías' => 'Encías'
                    ]),
                TextInput::make('marca')->required(),
                TextInput::make('litros')->numeric(),
                TextInput::make('lote')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('tipo'),
                TextColumn::make('marca'),
                TextColumn::make('litros'),
                TextColumn::make('lote')
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
            'index' => Pages\ListResinas::route('/'),
            'create' => Pages\CreateResina::route('/create'),
            'edit' => Pages\EditResina::route('/{record}/edit'),
        ];
    }
}
