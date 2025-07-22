<?php

namespace App\Filament\Widgets;

use App\Models\Factura;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Hugomyb\FilamentMediaAction\Tables\Actions\MediaAction;
use Storage;

class FacturasTableWidget extends BaseWidget
{

    protected int|string|array $columnSpan = '3';

    public static ?string $heading = 'Preview Facturas';
    public function table(Table $table): Table
    {
        return $table
            ->query(Factura::query()->latest()->limit(10)) // puedes ajustar la consulta a tu gusto
            ->columns([
                TextColumn::make('nombre')
                    ->badge()
                    ->color('black')->wrap()->description(function($record){
                        return $record->fecha_factura . ' - ' . $record->supplier->nombre;
                    }),

                // TextColumn::make('file')
                //     ->label('Documento')
                //     ->formatStateUsing(fn ($state) => $state ? basename($state) : '-')
                //     ->color('danger')
                //     ->badge()
                //     ->icon('heroicon-s-document-currency-euro'),

                // TextColumn::make('fecha_factura')
                //     ->badge()
                //     ->color('info'),

                // TextColumn::make('supplier.nombre')
                //     ->label('Proveedor')
                //     ->badge(),

                TextColumn::make('precio')
                    ->badge()
                    ->icon('heroicon-s-currency-euro')
                    ->iconPosition('after')
                    ->color('success')
                    ->label('Precio /€'),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),

                MediaAction::make('VerFactura')
                    ->iconButton()
                    ->label('Ver Factura')
                    ->icon('heroicon-s-document-magnifying-glass')
                    ->media(fn ($record) => Storage::url($record->file))
                    ->modalWidth('7xl')
                    ->disabled(fn ($record) => empty($record->file)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
