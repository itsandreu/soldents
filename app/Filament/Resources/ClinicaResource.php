<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Registro;
use App\Filament\Resources\ClinicaResource\Pages;
use App\Filament\Resources\ClinicaResource\RelationManagers;
use App\Filament\Resources\ClinicaResource\RelationManagers\PersonaRelationManager;
use App\Models\Clinica;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClinicaResource extends Resource
{
    protected static ?string $model = Clinica::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Work';

    protected static ?string $cluster = Registro::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Datos')
                    ->schema([
                        Section::make()->schema([
                            TextInput::make('nombre')->label("Nombre")->columnSpan(1),
                            TextInput::make('direccion')->label("Dirección")->columnSpan(1),
                            TextInput::make('telefono')->label("Número de teléfono")->columnSpan(1),
                            Textarea::make("descripcion")->label("Descripción")->columnSpan(1),
                        ])->columnSpan(1),
                        Section::make()->schema([
                            FileUpload::make('foto')
                            ->disk('public')
                            ->directory('clinicas')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                        ])->columnSpan(1)
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    ImageColumn::make('foto')->circular()->height(180)->alignCenter()->default(asset("storage/sinfoto.png")),
                    TextColumn::make('nombre')->size(20)->alignCenter()->weight('black')->size(10),
                    TextColumn::make('direccion')->icon("heroicon-s-map")->weight('semibold')->alignLeft()->iconColor('success'),
                    TextColumn::make('telefono')->icon("heroicon-s-phone")->weight('black')->alignLeft()->iconColor('danger'),
                    TextColumn::make('descripcion')->icon("heroicon-s-pencil")->alignLeft()->limit(30)->iconColor('sky')->color('description'),
                    TextColumn::make('created_at')->icon("heroicon-s-calendar")->alignLeft()->color("violet")->iconColor('violet')->weight('bold')->sinceTooltip()
                ])
            ])->contentGrid([
                'md' => 3,
                'xl' => 5,
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PersonaRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClinicas::route('/'),
            'create' => Pages\CreateClinica::route('/create'),
            'edit' => Pages\EditClinica::route('/{record}/edit'),
        ];
    }

    

}
