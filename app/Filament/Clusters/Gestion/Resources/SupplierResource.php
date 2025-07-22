<?php

namespace App\Filament\Clusters\Gestion\Resources;

use App\Filament\Clusters\Gestion;
use App\Filament\Clusters\Gestion\Resources\SupplierResource\Pages;
use App\Filament\Clusters\Gestion\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Proveedores';

    public static function getModelLabel(): string
    {
        return 'Proveedor';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Proveedores';
    }
    protected static ?string $cluster = Gestion::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns([
                        'sm' => 3,
                        'xl' => 6,
                        '2xl' => 8,
                    ])
                    ->schema([
                        Fieldset::make('Datos')
                            ->schema([
                                TextInput::make('nombre')->required()->helperText('Nombre del proveedor'),
                                TextInput::make('cif_nif')->label('Cif/Nif')->helperText('Introduzca el Cif o Nif segun sea necesario'),
                                TextInput::make('email')->helperText('Introduzca el Email de contacto'),
                                TextInput::make('telefono')->helperText('Introduzca el Nº Telefono coorporativo'),
                            ])->columnSpan(4),
                        Fieldset::make('Facturación')
                            ->schema([
                                TextInput::make('iban')->helperText('Si desea registrar los datos de faturacion, introduzca el IBAN del proveedor')->columnSpanFull(),
                            ])->columnSpan(4),
                        Fieldset::make('Ubicación')
                            ->schema([
                                TextInput::make('direccion'),
                                TextInput::make('codigo_postal'),
                                TextInput::make('ciudad'),
                                TextInput::make('provincia'),
                                TextInput::make('pais')->columnSpan(2),
                            ])->columnSpan(4),
                        Fieldset::make('Info')
                            ->schema([
                                MarkdownEditor::make('observaciones')->columnSpanFull(),
                            ])->columnSpan(4),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    TextColumn::make('nombre')
                        ->weight(FontWeight::Bold)
                        ->searchable()
                        ->sortable()->icon('heroicon-s-shopping-cart'),
                ]),
                Panel::make([
                    Grid::make(2)->schema([
                        TextColumn::make('email')->icon('heroicon-m-envelope')->limit(14)->tooltip(function($state){
                            return $state;
                        }),
                        TextColumn::make('cif_nif')->icon('heroicon-m-identification'),
                        TextColumn::make('telefono')->icon('heroicon-m-phone'),
                        TextColumn::make('direccion')->icon('heroicon-m-map-pin'),
                        TextColumn::make('codigo_postal')->icon('heroicon-m-map'),
                        TextColumn::make('ciudad')->icon('heroicon-m-building-office'),
                        TextColumn::make('provincia')->icon('heroicon-m-building-office'),
                        TextColumn::make('pais')->icon('heroicon-m-globe-alt'),
                        // TextColumn::make('observaciones')->icon('heroicon-m-document-text'),
                    ]),
                ])->collapsible()->collapsed(false),
            ])->contentGrid([
                    'md' => 1,
                    'xl' => 2,
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
