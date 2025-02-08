<?php

namespace App\Filament\Resources\TrabajoResource\Pages;

use App\Filament\Resources\TrabajoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;


class ListTrabajos extends ListRecords
{
    protected static string $resource = TrabajoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
{
    return [
        'Todos' => Tab::make(),
        'Pendiente' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('estado_id', 1)),
        'Diseñado' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('estado_id', 2)),
        'Fresado' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('estado_id', 3)),
        'Sinterizado' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('estado_id', 4)),
        'Terminado' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('estado_id', 5)),
    ];
}
}
