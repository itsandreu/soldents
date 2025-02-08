<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventarioResource\Pages;
use App\Filament\Resources\InventarioResource\RelationManagers;
use App\Models\Disco;
use App\Models\Fresa;
use App\Models\Inventario;
use App\Models\Resina;
use Filament\Forms;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Attributes\Reactive;

class InventarioResource extends Resource
{
    protected static ?string $model = Inventario::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $navigationGroup = 'Inventario';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')->required(),
                Textarea::make('descripcion')->required(),
                TextInput::make('cantidad')->numeric()->required(),
                MorphToSelect::make('inventariable')
                ->types([
                    MorphToSelect\Type::make(Fresa::class)->titleAttribute('tipo'),
                    MorphToSelect\Type::make(Disco::class)->titleAttribute('material'),
                    MorphToSelect\Type::make(Resina::class)->titleAttribute('tipo'),
                ])
                ->label('Tipo de Producto')
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                ImageColumn::make('Image')
                ->label('foto')
                ->defaultImageUrl(
                    function($record){
                        if ($record->inventariable_type == "App\Models\Fresa") 
                        {
                            return asset('storage/fresa.png');
                        }
                        elseif ($record->inventariable_type == "App\Models\Disco") 
                        {
                            return asset('storage/disco.png');
                        }
                        elseif($record->inventariable_type == "App\Models\Resina") 
                        {
                            return asset('storage/resina.png');
                        } 
                    })
                ->disk('public')
                ->square(),
                TextColumn::make('nombre'),
                TextColumn::make('descripcion'),
                TextColumn::make('cantidad')->badge(),
                // TextColumn::make('inventariable_type')->label('Tipo')->formatStateUsing(function($record){
                //     return class_basename($record->inventariable_type);
                // }),
                TextColumn::make('inventariable_id')->label('Tipo')->formatStateUsing(function($record,$state){

                    if ($record->inventariable_type == "App\Models\Fresa") {
                        $valor = Fresa::where('id',$state)->first();
                        return  class_basename($record->inventariable_type) . ": " . $valor->tipo;
                    }elseif ($record->inventariable_type == "App\Models\Disco") {
                        $valor = Disco::where('id',$state)->first();
                        return class_basename($record->inventariable_type) . ": " . $valor->material;
                    }elseif($record->inventariable_type == "App\Models\Resina") {
                        $valor = Resina::where('id',$state)->first();
                        return class_basename($record->inventariable_type) . ": " . $valor->tipo;
                    }
                })
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
            'index' => Pages\ListInventarios::route('/'),
            'create' => Pages\CreateInventario::route('/create'),
            'edit' => Pages\EditInventario::route('/{record}/edit'),
        ];
    }
}
