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
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Reactive;

class InventarioResource extends Resource
{
    protected static ?string $model = Inventario::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $navigationGroup = 'Inventario';

    protected static ?string $title = 'Inventario';

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
                ->square()->size(120),
                TextColumn::make('nombre')->badge()->color('violet')->description(fn (Inventario $record) => new HtmlString(
                    !$record || !$record->inventariable ? 'No hay información adicional disponible' : (
                        $record->inventariable instanceof Fresa ? "<table style='border-collapse: collapse; width: 100%; text-align: center;'>
                            <tr><td style=' padding: 1px;'><b>Tipo</b></td><td style=' padding: 1px;'>{$record->inventariable->tipo}</td></tr>
                            <tr><td style=' padding: 1px;'><b>Material</b></td><td style=' padding: 1px;'>{$record->inventariable->material}</td></tr>
                            <tr><td style=' padding: 1px;'><b>Marca</b></td><td style=' padding: 1px;'>{$record->inventariable->marca}</td></tr>
                            <tr><td style=' padding: 1px;'><b>Diámetro</b></td><td style=' padding: 1px;'>{$record->inventariable->diametro}</td></tr>
                        </table>" : (
                        $record->inventariable instanceof Disco ? "<table style='border-collapse: collapse; width: 100%; text-align: center;'>
                            <tr><td style=' padding: 1px;'><b>Material</b></td><td style=' padding: 1px;'>{$record->inventariable->material}</td></tr>
                            <tr><td style=' padding: 1px;'><b>Marca</b></td><td style=' padding: 1px;'>{$record->inventariable->marca}</td></tr>
                            <tr><td style=' padding: 1px;'><b>Color</b></td><td style=' padding: 1px;'>{$record->inventariable->color}</td></tr>
                            <tr><td style=' padding: 1px;'><b>Translucidez</b></td><td style=' padding: 1px;'>{$record->inventariable->translucidez}</td></tr>
                            <tr><td style=' padding: 1px;'><b>Dimensiones</b></td><td style=' padding: 1px;'>{$record->inventariable->dimensiones}</td></tr>
                            <tr><td style=' padding: 1px;'><b>Reducción</b></td><td style=' padding: 1px;'>{$record->inventariable->reduccion}</td></tr>
                            <tr><td style=' padding: 1px;'><b>Lote</b></td><td style=' padding: 1px;'>{$record->inventariable->lote}</td></tr>
                        </table>" : (
                        $record->inventariable instanceof Resina ? "<table style='border-collapse: collapse; width: 100%; text-align: center;'>
                            <tr><td style=' padding: 1px;'><b>Tipo</b></td><td style=' padding: 1px;'>{$record->inventariable->tipo}</td></tr>
                            <tr><td style=' padding: 1px;'><b>Marca</b></td><td style=' padding: 1px;'>{$record->inventariable->marca}</td></tr>
                            <tr><td style=' padding: 1px;'><b>Litros</b></td><td style=' padding: 1px;'>{$record->inventariable->litros}</td></tr>
                            <tr><td style=' padding: 1px;'><b>Lote</b></td><td style=' padding: 1px;'>{$record->inventariable->lote}</td></tr>
                        </table>" : ''
                    )))
                ))->wrap()->html(),
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
