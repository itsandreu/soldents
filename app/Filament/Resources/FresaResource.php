<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Inventario;
use App\Filament\Resources\FresaResource\Pages;
use App\Filament\Resources\FresaResource\RelationManagers;
use App\Models\Fresa;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FresaResource extends Resource
{
    protected static ?string $model = Fresa::class;

    protected static ?string $navigationIcon = 'heroicon-o-stop';

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
                            ->description('Introduce las características básicas del material, incluyendo su tipo, la marca comercial y el material. Esta sección permite identificar el producto de forma visual y técnica para su correcta clasificación.')
                            ->schema([
                                Select::make('tipo')->options([
                                    'Mano' => 'Mano',
                                    'Fresadora' => 'Fresadora'
                                ]),
                                TextInput::make('material')->required(),
                                TextInput::make('marca')->required(),
                            ])->columnSpan(3),
                        Section::make('Datos')
                            ->description('Introduce las características básicas del material, incluyendo su diametro, las unidades del articulo y el status. Esta sección permite identificar el producto de forma visual y técnica para su correcta clasificación.')
                            ->schema([
                                TextInput::make('diametro')->numeric()->required(),
                                TextInput::make('unidades')->numeric()->required()->afterStateUpdated(function ($state, callable $set) {
                                    if ($state == 0) {
                                        $set('status', 'sin stock'); // Esto actualiza el campo 'status' en el formulario
                                    } elseif ($state > 0) {
                                        $set('status', 'stock');
                                    }
                                })->live(),
                                Select::make('status')
                                ->options([
                                    'stock' => 'stock',
                                    'sin stock' => 'sin stock',
                                    'en uso' => 'en uso',
                                ])->required()
                            ])->columnSpan(3)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                SelectColumn::make('status')
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
                ->sortable()
                ->extraAttributes(fn($record) => [
                    'class' => match ($record->status) {
                        'stock' => 'bg-violet-300 text-green-800',
                        'sin stock' => 'bg-gray-300 text-yellow-800',
                        'en uso' => 'bg-green-500 text-blue-800',
                        default => '',
                    },
                ]),
                TextColumn::make('tipo'),
                TextColumn::make('material'),
                TextColumn::make('marca'),
                TextColumn::make('diametro'),
                TextColumn::make('unidades')->badge()->color(function($state){
                    if ($state < 2 ) {
                        return 'danger';
                    }elseif ($state > 2) {
                        return 'success';
                    }elseif ($state = 2 ) {
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
            'index' => Pages\ListFresas::route('/'),
            'create' => Pages\CreateFresa::route('/create'),
            'edit' => Pages\EditFresa::route('/{record}/edit'),
        ];
    }
}
