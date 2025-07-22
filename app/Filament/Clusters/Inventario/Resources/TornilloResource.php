<?php

namespace App\Filament\Clusters\Inventario\Resources;

use App\Filament\Clusters\Inventario;
use App\Filament\Clusters\Inventario\Resources\TornilloResource\Pages;
use App\Filament\Clusters\Inventario\Resources\TornilloResource\RelationManagers;
use App\Models\Tornillo;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TornilloResource extends Resource
{
    protected static ?string $model = Tornillo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Artículos';

    protected static ?string $cluster = Inventario::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns([
                        'sm' => 2,
                        'xl' => 4,
                        '2xl' => 6,
                    ])
                    ->schema([
                        Section::make('Datos')
                            ->description('Introduce las características básicas del material, incluyendo su marca comercial y el tipo de tornillo y modelo. Esta sección permite identificar el producto de forma visual y técnica para su correcta clasificación.')
                            ->schema([
                                Select::make('tornillo_marca_id')->relationship('marca', 'nombre')
                                    ->createOptionForm([
                                        TextInput::make('nombre')
                                            ->required(),
                                    ]),
                                Select::make('tornillo_modelo_id')->relationship('modelo', 'nombre')
                                    ->createOptionForm([
                                        TextInput::make('nombre')
                                            ->required(),
                                    ]),
                                Select::make('tornillo_tipo_id')->relationship('tipo', 'nombre')
                                    ->createOptionForm([
                                        TextInput::make('nombre')
                                            ->required(),
                                    ]),
                            ])->columnSpan(3),
                        Section::make('Referencias')
                            ->description('Asocia valores clave de control logístico y seguimiento, como el estado actual del material y la cantidad de unidades disponibles y el numero de referencia. Estos datos facilitan la trazabilidad y gestión del inventario.')
                            ->schema([
                                TextInput::make('referencia')->required(),
                                TextInput::make('unidades')->numeric()->required()->step(1),
                            ])->columnSpan(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordClasses(fn(Model $record) => match ($record->status) {
                'stock' => 'bg-gray-100 text-gray-600 italic',
                'sin stock' => 'opacity-40 bg-orange-100 font-semibold italic',
                'en uso' => 'bg-green-100 text-green-800 font-bold italic',
                default => 'bg-white',
            })
            ->columns([
                // SelectColumn::make('status')->label('Estado')
                //     ->options([
                //         'stock' => 'Stock',
                //         'sin stock' => 'Sin stock',
                //         'en uso' => 'En uso',
                //     ])->rules(function ($record) {
                //         return [
                //             function (string $attribute, $value, Closure $fail) use ($record) {
                //                 if ($value === 'stock' && $record->unidades == 0) {
                //                     $fail('No puedes marcar como "stock" si no quedan unidades');
                //                 } elseif ($value === 'en uso' && $record->unidades == 0) {
                //                     $fail('No puedes marcar como "stock" si no quedan unidades');
                //                 } elseif ($value === 'sin stock' && $record->unidades > 0) {
                //                     $fail('No puedes marcar como "sin stock" si aún quedan unidades');
                //                 }
                //             },
                //         ];
                //     })
                //     ->sortable(),
                TextColumn::make('marca.nombre')->sortable(),
                TextColumn::make('tipo.nombre')->sortable(),
                TextColumn::make('modelo.nombre')->sortable(),
                TextColumn::make('referencia')->sortable()->badge()->color('violet'),
                TextColumn::make('unidades')->sortable()->badge()
                ->color(function($state){
                        if($state == '0'){
                            return 'danger';
                        }else{
                            return 'sky';
                        }
                }),
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
            'index' => Pages\ListTornillos::route('/'),
            'create' => Pages\CreateTornillo::route('/create'),
            'edit' => Pages\EditTornillo::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return self::$model::count();
    }
}
