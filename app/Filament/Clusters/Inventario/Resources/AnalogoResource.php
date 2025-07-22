<?php

namespace App\Filament\Clusters\Inventario\Resources;

use App\Filament\Clusters\Inventario;
use App\Filament\Clusters\Inventario\Resources\AnalogoResource\Pages;
use App\Filament\Clusters\Inventario\Resources\AnalogoResource\RelationManagers;
use App\Models\Analogo;
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

class AnalogoResource extends Resource
{
    protected static ?string $model = Analogo::class;

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
                            ->description('Introduce las características básicas del material, incluyendo su marca comercial y el tipo de interfase. Esta sección permite identificar el producto de forma visual y técnica para su correcta clasificación.')
                            ->schema([
                                Select::make('analogo_marca_id')->relationship('marca', 'nombre')
                                    ->createOptionForm([
                                        TextInput::make('nombre')
                                            ->required(),
                                    ]),
                                Select::make('analogo_modelo_id')->relationship('modelo', 'nombre')
                                    ->createOptionForm([
                                        TextInput::make('nombre')
                                            ->required(),
                                    ]),
                            ])->columnSpan(2),
                        Section::make('Ajustes')
                            ->description('Introduce las características básicas del material, incluyendo su marca comercial y el tipo de interfase. Esta sección permite identificar el producto de forma visual y técnica para su correcta clasificación.')
                            ->schema([
                                Select::make('analogo_diametro_id')->relationship('diametro', 'valor')
                                    ->createOptionForm([
                                        TextInput::make('valor')
                                            ->numeric()->required(),
                                    ]),
                            ])->columnSpan(2),
                        Section::make('Referencias')
                            ->description('Introduce las características básicas del material, incluyendo su marca comercial y el tipo de interfase. Esta sección permite identificar el producto de forma visual y técnica para su correcta clasificación.')
                            ->schema([
                                TextInput::make('referencia')->required(),
                                TextInput::make('unidades')->numeric()->required()->step(1)
                                // ->afterStateUpdated(function ($state, callable $set) {
                                //     if ($state == 0) {
                                //         $set('status', 'sin stock'); // Esto actualiza el campo 'status' en el formulario
                                //     } elseif ($state > 0) {
                                //         $set('status', 'stock');
                                //     }
                                // })->live(),
                                // Select::make('status')
                                //     ->options([
                                //         'stock' => 'stock',
                                //         'sin stock' => 'sin stock',
                                //         'en uso' => 'en uso',
                                //     ])->required(),
                            ])->columnSpan(2)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordClasses(fn(Model $record) => match ($record->status) {
                'stock' => 'bg-gray-100 text-gray-600 italic',
                'sin stock' => 'opacity-40 bg-orange-100 font-semibold',
                'en uso' => 'bg-green-100 text-green-800 font-bold',
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
                TextColumn::make('modelo.nombre')->sortable(),
                TextColumn::make('diametro.valor')->sortable(),
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
            'index' => Pages\ListAnalogos::route('/'),
            'create' => Pages\CreateAnalogo::route('/create'),
            'edit' => Pages\EditAnalogo::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return self::$model::count();
    }
}
