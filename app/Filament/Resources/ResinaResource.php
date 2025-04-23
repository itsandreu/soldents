<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Inventario;
use App\Filament\Resources\ResinaResource\Pages;
use App\Filament\Resources\ResinaResource\RelationManagers;
use App\Models\Resina;
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

class ResinaResource extends Resource
{
    protected static ?string $model = Resina::class;

    protected static ?string $navigationIcon = 'heroicon-o-lifebuoy';

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
                    ])->schema([
                        Section::make('Datos')
                            ->description('Introduce las características básicas del material, incluyendo su tipo y la marca comercial. Esta sección permite identificar el producto de forma visual y técnica para su correcta clasificación.')
                            ->schema([
                                Select::make('tipo')
                                    ->options([
                                        'Modelos' => 'Modelos',
                                        'Férulas' => 'Férulas',
                                        'Encías' => 'Encías'
                                    ]),
                                TextInput::make('marca')->required(),
                            ])->columnSpan(2),
                        Section::make('Medida')
                            ->description('Introduce los litros que contiene este articulo.')
                            ->schema([
                                TextInput::make('litros')->numeric(),
                            ])->columnSpan(2),
                        Section::make('Referencias')
                            ->description('Introduce las características básicas del material, incluyendo su Lote, las unidades del articulo y el status. Esta sección permite identificar el producto de forma visual y técnica para su correcta clasificación.')
                            ->schema([
                                TextInput::make('lote')->required(),
                                TextInput::make('unidades')->numeric()->afterStateUpdated(function ($state, callable $set) {
                                    if ($state == 0) {
                                        $set('status', 'sin stock'); // Esto actualiza el campo 'status' en el formulario
                                    } elseif ($state > 0) {
                                        $set('status', 'stock');
                                    }
                                })->live(),
                                Select::make('status')->options([
                                    'stock' => 'stock',
                                    'sin stock' => 'sin stock',
                                    'en uso' => 'en uso',
                                ])->required(),
                            ])->columnSpan(2)
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
                // TextColumn::make('id'),
                SelectColumn::make('status')->label('Estado')
                    ->options([
                        'stock' => 'Stock',
                        'sin stock' => 'Sin stock',
                        'en uso' => 'En uso',
                    ])->rules(function ($record) {
                        return [
                            function (string $attribute, $value, Closure $fail) use ($record) {
                                if ($value === 'stock' && $record->unidades == 0) {
                                    $fail('No puedes marcar como "stock" si no quedan unidades');
                                } elseif ($value === 'en uso' && $record->unidades == 0) {
                                    $fail('No puedes marcar como "stock" si no quedan unidades');
                                } elseif ($value === 'sin stock' && $record->unidades > 0) {
                                    $fail('No puedes marcar como "sin stock" si aún quedan unidades');
                                }
                            },
                        ];
                    })
                    ->sortable(),
                TextColumn::make('tipo'),
                TextColumn::make('marca'),
                TextColumn::make('litros'),
                TextColumn::make('lote'),
                TextColumn::make('unidades')->badge()->color(function ($state) {
                    if ($state < 2) {
                        return 'danger';
                    } elseif ($state > 2) {
                        return 'success';
                    } elseif ($state = 2) {
                        return 'warning';
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
            'index' => Pages\ListResinas::route('/'),
            'create' => Pages\CreateResina::route('/create'),
            'edit' => Pages\EditResina::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return self::$model::count();
    }
}
