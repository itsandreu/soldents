<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Inventario;
use App\Filament\Resources\DiscoResource\Pages;
use App\Filament\Resources\DiscoResource\RelationManagers;
use App\Models\Disco;
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

class DiscoResource extends Resource
{
    protected static ?string $model = Disco::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

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
                            ->description('Introduce las características básicas del material, incluyendo su composición, la marca comercial y el color. Esta sección permite identificar el producto de forma visual y técnica para su correcta clasificación.')
                            ->schema([
                                TextInput::make('material')->required()->columnSpan(2),
                                TextInput::make('marca')->required()->columnSpan(2),
                                TextInput::make('color')->required()->columnSpan(2),
                            ])->columnSpan(2),
                        Section::make('Ajustes')
                            ->description('Define los parámetros técnicos y físicos del material, como su nivel de translucidez, las dimensiones específicas y cualquier factor de reducción. Esta información es crucial para ajustar su uso ')
                            ->schema([  
                                TextInput::make('translucidez')->required()->columnSpan(2),
                                TextInput::make('dimensiones')->required()->columnSpan(2),
                                TextInput::make('reduccion')->numeric()->required()->columnSpan(2),
                            ])->columnSpan(2),
                        Section::make('Referencias')
                            ->description('Asocia valores clave de control logístico y seguimiento, como el estado actual del material, la cantidad de unidades disponibles y el número de lote. Estos datos facilitan la trazabilidad y gestión del inventario.')
                            ->schema([ 
                                TextInput::make('unidades')->numeric()->required()->step(1)->afterStateUpdated(function ($state, callable $set) {
                                    if ($state == 0) {
                                        $set('status', 'sin stock'); // Esto actualiza el campo 'status' en el formulario
                                    } elseif ($state > 0) {
                                        $set('status', 'stock');
                                    }
                                })->live()->columnSpan(2),
                                TextInput::make('lote')->required()->columnSpan(2),
                                Select::make('status')
                                ->options([
                                    'stock' => 'stock',
                                    'sin stock' => 'sin stock',
                                    'en uso' => 'en uso',
                                ])->required()->columnSpan(2)
                            ])->columnSpan(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('id'),
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
                TextColumn::make('material')->sortable(),
                TextColumn::make('marca')->sortable(),
                TextColumn::make('color')->sortable(),
                TextColumn::make('translucidez')->sortable(),
                TextColumn::make('dimensiones')->sortable(),
                TextColumn::make('reduccion')->sortable(),
                TextColumn::make('lote')->sortable(),
                TextColumn::make('unidades')->sortable()->badge()->color(function($state){
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
            'index' => Pages\ListDiscos::route('/'),
            'create' => Pages\CreateDisco::route('/create'),
            'edit' => Pages\EditDisco::route('/{record}/edit'),
        ];
    }
}
