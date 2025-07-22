<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Gestion;
use App\Filament\Clusters\Gestion\Resources\FacturaResource\Widgets\FacturasApexChart;
use App\Filament\Resources\FacturaResource\Pages;
use App\Filament\Resources\FacturaResource\RelationManagers;
use App\Models\Factura;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Hugomyb\FilamentMediaAction\Tables\Actions\MediaAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Support\Facades\Storage;

class FacturaResource extends Resource
{
    protected static ?string $model = Factura::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-euro';

    protected static ?string $cluster = Gestion::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->description('Introduzca los datos para registrar las facturas')
                ->schema([
                    TextInput::make('nombre')->required(),
                    FileUpload::make('file')
                        ->disk('public')
                        ->label('Factura')
                        ->directory('facturas')
                        ->preserveFilenames()->downloadable(),

                    DatePicker::make('fecha_factura')->required(),
                    TextInput::make('precio')->required()->numeric(),
                    Select::make('supplier_id')->options(Supplier::pluck('nombre','id'))
                ])->columnSpan(1)
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')->badge()->color('black'),

                TextColumn::make('file')->label('Documento')->formatStateUsing(fn ($state) => $state ? basename($state) : '-')->color('danger')->badge()->icon('heroicon-s-document-currency-euro'),
                TextColumn::make('fecha_factura')->badge()->color('info'),
                TextColumn::make('supplier.nombre')->label('Proveedor')->badge(),
                TextColumn::make('precio')->badge()->icon('heroicon-s-currency-euro')->color('success')->iconPosition('after')->label('Precio /€'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                MediaAction::make('VerFactura')
                ->iconButton()
                ->label('Ver Factura')
                ->icon('heroicon-s-document-magnifying-glass')
                ->media(fn ($record) => Storage::url($record->file))->modalWidth('7xl')
                ->disabled(fn ($record) => empty($record->file))
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
            'index' => Pages\ListFacturas::route('/'),
            'create' => Pages\CreateFactura::route('/create'),
            'edit' => Pages\EditFactura::route('/{record}/edit'),
        ];
    }

    public function getWidgetsColumns(): int
{
    return 2; // ahora el grid del recurso tendrá 6 columnas
}
}
