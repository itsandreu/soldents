<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\TrabajoResource;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Storage;

class TrabajosTableWidget extends BaseWidget
{
    protected int|string|array $columnSpan = '5';

    public static ?string $heading = 'Preview Trabajos';

    public static ?string $description = 'Previsualización de todos los trabajos';
    public function table(Table $table): Table
    {
       return $table
            ->query(\App\Models\Trabajo::query()) // o aplica tu lógica de filtrado
            ->columns(TrabajoResource::columnasPersonalizadas())->defaultSort('id', 'desc')->poll(2);
            // ->actions([
                    //  ActionGroup::make([
                    //         Action::make('0')
                    //         ->label(function ($record) {
                    //             return basename($record->files[0]);
                    //         })
                    //         ->icon('heroicon-o-eye')
                    //         ->modalWidth('6xl')
                    //         ->modalHeading('Vista previa 3D del modelo STL')
                    //         ->modalSubmitAction(false)
                    //         ->modalCancelActionLabel('Cerrar')
                    //         ->modalContent(fn($record) => view('components.stl-viewer', [
                    //             // Si tu columna ya está casteada a array:
                    //             'url' => asset(Storage::url($record->files[0])),
                    //         ]))->extraAttributes([
                    //                 'class' => 'whitespace-normal break-words justify-center text-center',
                    //             ])
                    //         ->visible(function ($record) {
                    //             $files = $record->files;

                    //             if (is_string($files)) {
                    //                 $files = json_decode($files, true) ?: [];
                    //             } elseif (!is_array($files)) {
                    //                 $files = (array) $files;
                    //             }

                    //             return !empty($files) && isset($files[0]) && !empty($files[0]);
                    //         }),
                    //     Action::make('1')
                    //         ->label(function ($record) {
                    //             return basename($record->files[1]);
                    //         })
                    //         ->icon('heroicon-o-eye')
                    //         ->modalWidth('6xl')
                    //         ->modalHeading('Vista previa 3D del modelo STL')
                    //         ->modalSubmitAction(false)
                    //         ->modalCancelActionLabel('Cerrar')
                    //         ->modalContent(fn($record) => view('components.stl-viewer', [
                    //             // Si tu columna ya está casteada a array:
                    //             'url' => asset(Storage::url($record->files[1])),
                    //         ]))
                    //         ->visible(function ($record) {
                    //             $files = $record->files;

                    //             if (is_string($files)) {
                    //                 $files = json_decode($files, true) ?: [];
                    //             } elseif (!is_array($files)) {
                    //                 $files = (array) $files;
                    //             }

                    //             return !empty($files) && isset($files[1]) && !empty($files[1]);
                    //         }),
                    //     Action::make('2')
                    //         ->label(function ($record) {
                    //             return basename($record->files[2]);
                    //         })
                    //         ->icon('heroicon-o-eye')
                    //         ->modalWidth('6xl')
                    //         ->modalHeading('Vista previa 3D del modelo STL')
                    //         ->modalSubmitAction(false)
                    //         ->modalCancelActionLabel('Cerrar')
                    //         ->modalContent(fn($record) => view('components.stl-viewer', [
                    //             // Si tu columna ya está casteada a array:
                    //             'url' => asset(Storage::url($record->files[2])),
                    //         ]))
                    //         ->visible(function ($record) {
                    //             $files = $record->files;

                    //             if (is_string($files)) {
                    //                 $files = json_decode($files, true) ?: [];
                    //             } elseif (!is_array($files)) {
                    //                 $files = (array) $files;
                    //             }

                    //             return !empty($files) && isset($files[2]) && !empty($files[2]);
                    //         }),
                    //     Action::make('3')
                    //         ->label(function ($record) {
                    //             return basename($record->files[3]);
                    //         })
                    //         ->icon('heroicon-o-eye')
                    //         ->modalWidth('6xl')
                    //         ->modalHeading('Vista previa 3D del modelo STL')
                    //         ->modalSubmitAction(false)
                    //         ->modalCancelActionLabel('Cerrar')
                    //         ->modalContent(fn($record) => view('components.stl-viewer', [
                    //             // Si tu columna ya está casteada a array:
                    //             'url' => asset(Storage::url($record->files[3])),
                    //         ]))
                    //         ->visible(function ($record) {
                    //             $files = $record->files;

                    //             if (is_string($files)) {
                    //                 $files = json_decode($files, true) ?: [];
                    //             } elseif (!is_array($files)) {
                    //                 $files = (array) $files;
                    //             }

                    //             return !empty($files) && isset($files[3]) && !empty($files[3]);
                    //         }),
                    //     Action::make('4')
                    //         ->label(function ($record) {
                    //             return basename($record->files[4]);
                    //         })
                    //         ->icon('heroicon-o-eye')
                    //         ->modalWidth('6xl')
                    //         ->modalHeading('Vista previa 3D del modelo STL')
                    //         ->modalSubmitAction(false)
                    //         ->modalCancelActionLabel('Cerrar')
                    //         ->modalContent(fn($record) => view('components.stl-viewer', [
                    //             // Si tu columna ya está casteada a array:
                    //             'url' => asset(Storage::url($record->files[4])),
                    //         ]))
                    //         ->visible(function ($record) {
                    //             $files = $record->files;

                    //             if (is_string($files)) {
                    //                 $files = json_decode($files, true) ?: [];
                    //             } elseif (!is_array($files)) {
                    //                 $files = (array) $files;
                    //             }

                    //             return !empty($files) && isset($files[4]) && !empty($files[4]);
                    //         }),
                    //     Action::make('5')
                    //         ->label(function ($record) {
                    //             return basename($record->files[5]);
                    //         })
                    //         ->icon('heroicon-o-eye')
                    //         ->modalWidth('6xl')
                    //         ->modalHeading('Vista previa 3D del modelo STL')
                    //         ->modalSubmitAction(false)
                    //         ->modalCancelActionLabel('Cerrar')
                    //         ->modalContent(fn($record) => view('components.stl-viewer', [
                    //             // Si tu columna ya está casteada a array:
                    //             'url' => asset(Storage::url($record->files[5])),
                    //         ]))
                    //         ->visible(function ($record) {
                    //             $files = $record->files;

                    //             if (is_string($files)) {
                    //                 $files = json_decode($files, true) ?: [];
                    //             } elseif (!is_array($files)) {
                    //                 $files = (array) $files;
                    //             }

                    //             return !empty($files) && isset($files[5]) && !empty($files[5]);
                    //         }),
                    //     Action::make('6')
                    //         ->label(function ($record) {
                    //             return basename($record->files[6]);
                    //         })
                    //         ->icon('heroicon-o-eye')
                    //         ->modalWidth('6xl')
                    //         ->modalHeading('Vista previa 3D del modelo STL')
                    //         ->modalSubmitAction(false)
                    //         ->modalCancelActionLabel('Cerrar')
                    //         ->modalContent(fn($record) => view('components.stl-viewer', [
                    //             // Si tu columna ya está casteada a array:
                    //             'url' => asset(Storage::url($record->files[6])),
                    //         ]))
                    //         ->visible(function ($record) {
                    //             $files = $record->files;

                    //             if (is_string($files)) {
                    //                 $files = json_decode($files, true) ?: [];
                    //             } elseif (!is_array($files)) {
                    //                 $files = (array) $files;
                    //             }

                    //             return !empty($files) && isset($files[6]) && !empty($files[6]);
                    //         }),
                    //     Action::make('7')
                    //         ->label(function ($record) {
                    //             return basename($record->files[7]);
                    //         })
                    //         ->icon('heroicon-o-eye')
                    //         ->modalWidth('6xl')
                    //         ->modalHeading('Vista previa 3D del modelo STL')
                    //         ->modalSubmitAction(false)
                    //         ->modalCancelActionLabel('Cerrar')
                    //         ->modalContent(fn($record) => view('components.stl-viewer', [
                    //             // Si tu columna ya está casteada a array:
                    //             'url' => asset(Storage::url($record->files[7])),
                    //         ]))
                    //         ->visible(function ($record) {
                    //             $files = $record->files;

                    //             if (is_string($files)) {
                    //                 $files = json_decode($files, true) ?: [];
                    //             } elseif (!is_array($files)) {
                    //                 $files = (array) $files;
                    //             }

                    //             return !empty($files) && isset($files[7]) && !empty($files[7]);
                    //         }),
                    //     Action::make('8')
                    //         ->label(function ($record) {
                    //             return basename($record->files[8]);
                    //         })
                    //         ->icon('heroicon-o-eye')
                    //         ->modalWidth('6xl')
                    //         ->modalHeading('Vista previa 3D del modelo STL')
                    //         ->modalSubmitAction(false)
                    //         ->modalContent(fn($record) => view('components.stl-viewer', [
                    //             // Si tu columna ya está casteada a array:
                    //             'url' => asset(Storage::url($record->files[8])),
                    //         ]))
                    //         ->visible(function ($record) {
                    //             $files = $record->files;

                    //             if (is_string($files)) {
                    //                 $files = json_decode($files, true) ?: [];
                    //             } elseif (!is_array($files)) {
                    //                 $files = (array) $files;
                    //             }

                    //             return !empty($files) && isset($files[8]) && !empty($files[8]);
                    //         }),
                    //     Action::make('9')
                    //         ->label(function ($record) {
                    //             return basename($record->files[9]);
                    //         })
                    //         ->icon('heroicon-o-eye')
                    //         ->modalWidth('6xl')
                    //         ->modalHeading('Vista previa 3D del modelo STL')
                    //         ->modalSubmitAction(false)
                    //         ->modalContent(fn($record) => view('components.stl-viewer', [
                    //             // Si tu columna ya está casteada a array:
                    //             'url' => asset(Storage::url($record->files[9])),
                    //         ]))
                    //         ->visible(function ($record) {
                    //             $files = $record->files;

                    //             if (is_string($files)) {
                    //                 $files = json_decode($files, true) ?: [];
                    //             } elseif (!is_array($files)) {
                    //                 $files = (array) $files;
                    //             }

                    //             return !empty($files) && isset($files[9]) && !empty($files[9]);
                    //         }),
            //          ])
            // ])
            
    }
}
