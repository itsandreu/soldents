<?php

namespace App\Filament\Clusters\Inventario\Resources;

use App\Filament\Clusters\Inventario;
use App\Filament\Clusters\Inventario\Resources\InterfaseResource\Pages;
use App\Filament\Clusters\Inventario\Resources\InterfaseResource\RelationManagers;
use App\Models\Interfase;
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

class InterfaseResource extends Resource
{
    protected static ?string $model = Interfase::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Consumibles';

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
                                Select::make('interfase_marca_id')->relationship('marca', 'nombre')
                                    ->createOptionForm([
                                        TextInput::make('nombre')
                                            ->required(),
                                    ]),
                                Select::make('interfase_tipo_id')->relationship('tipo', 'nombre')
                                    ->createOptionForm([
                                        TextInput::make('nombre')
                                            ->required(),
                                    ]),
                        ])->columnSpan(2),
                        Section::make('Ajustes')
                            ->description('Define los parámetros técnicos y físicos del material, como su diametro, las alturasG y altruasH. Esta información es crucial para ajustar su uso ')
                            ->schema([
                                Select::make('interfase_diametro_id')->relationship('diametro', 'valor')
                                    ->createOptionForm([
                                        TextInput::make('valor')
                                            ->required(),
                                    ])->columnSpan(2),
                                Select::make('interfase_altura_g_id')->relationship('alturaG', 'valor')
                                    ->createOptionForm([
                                        TextInput::make('valor')
                                            ->required(),
                                    ])->columnSpan(1),
                                Select::make('interfase_altura_h_id')->relationship('alturaH', 'nombre')
                                    ->createOptionForm([
                                        TextInput::make('nombre')->label('valor')
                                            ->required(),
                                    ])->columnSpan(1),
                                Select::make('rotacion')
                                    ->options([
                                        'antirrotatorio' => 'Antirrotatorio',
                                        'rotatorio' => 'Rotatorio',
                                    ])->required()->columnSpan(2)
                        ])->columnSpan(2),
                        Section::make('Referencias')
                            ->description('Asocia valores clave de control logístico y seguimiento, como el estado actual del material y la cantidad de unidades disponibles. Estos datos facilitan la trazabilidad y gestión del inventario.')
                            ->schema([
                                TextInput::make('unidades')->numeric()->required()->step(1)->afterStateUpdated(function ($state, callable $set) {
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
                                    ])->required(),
                                TextInput::make('referencia')->required()
                            ])->columnSpan(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                    TextColumn::make('marca.nombre')->sortable(),
                    TextColumn::make('tipo.nombre')->sortable(),
                    TextColumn::make('diametro.valor')->sortable(),
                    TextColumn::make('alturaH.nombre')->sortable(),
                    TextColumn::make('alturaG.valor')->sortable(),
                    TextColumn::make('referencia')->sortable()->badge()->color('violet'),
                    TextColumn::make('unidades')->sortable(),
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
            'index' => Pages\ListInterfases::route('/'),
            'create' => Pages\CreateInterfase::route('/create'),
            'edit' => Pages\EditInterfase::route('/{record}/edit'),
        ];
    }
}
