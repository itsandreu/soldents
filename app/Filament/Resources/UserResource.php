<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\ImageColor;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?String $navigationLabel = 'Usuarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->columns([
                    'sm' => 1,
                    'xl' => 2,
                    '2xl' => 2,
                ])
                ->schema([
                    TextInput::make('name')->label('Nombre')
                        ->required()
                        ->maxLength(255)->columnSpan(1),
                    ToggleButtons::make('role')->label('Rol')
                        ->options(array_combine(User::ROLES, array_map('ucfirst', User::ROLES)))
                        ->required()->columnSpan(1)->inline()->grouped()
                        ->icons([
                            'admin' => 'heroicon-o-shield-check',
                            'editor' => 'heroicon-o-pencil',
                            'user' => 'heroicon-o-user',
                        ]),
                    FileUpload::make('foto')
                        ->image()
                        ->directory('users')
                        ->imageEditor() // opcional, para recortar
                        ->imagePreviewHeight('100')
                        ->maxSize(2048) // 2MB
                        ->nullable()->columnSpan(1),
                    TextInput::make('password')->label('Contraseña')
                        ->password()
                        ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                        ->minLength(8)
                        ->same('passwordConfirmation')
                        ->dehydrated(fn ($state) => filled ($state))
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                    TextInput::make('email')->label('Correo')
                        ->email()
                        ->required()
                        ->maxLength(255)->columnSpan(1),
                    TextInput::make('passwordConfirmation')
                        ->password()->label('Confirmar Contraseña')
                        ->label('Confirmar Contraseña')
                        ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                        ->minLength(8)
                        ->dehydrated(false),
                    ])
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto_url')->label('Foto')->getStateUsing(fn ($record) => $record->foto_url ?: asset('storage/default-icon.png'))->circular(),
                TextColumn::make('name')->label('Nombre'),
                TextColumn::make('email')->label('Correo'),
                TextColumn::make('role')->badge()->icon(function ($state){
                    if ($state === 'admin') {
                        return 'heroicon-m-shield-check';
                    }elseif ($state === 'editor') {
                        return 'heroicon-o-pencil';
                    }else {
                        return 'heroicon-o-user';
                    }
                })->color(function ($state){
                    if ($state === 'admin') {
                        return 'success';
                    }elseif ($state === 'editor') {
                        return 'warning';
                    }else {
                        return 'primary';
                    }
                })->label('Rol')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
