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
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\ImageColor;
use Filament\Facades\Filament;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Actions\Action;

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
                        ])->disableOptionWhen(function ($value): bool {
                            $user = Filament::auth()->user();
                            return $user->role !== 'admin';
                        }),
                    TextInput::make('email')->label('Correo')
                        ->email()
                        ->required()
                        ->maxLength(255)->columnSpan(1),
                    TextInput::make('password')->label('Contraseña')
                        ->password()
                        ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                        ->minLength(8)
                        ->same('passwordConfirmation')
                        ->dehydrated(fn ($state) => filled ($state))
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                    FileUpload::make('foto')
                        ->image()
                        ->directory('users')
                        ->imageEditor() // opcional, para recortar
                        ->imagePreviewHeight('100')
                        ->maxSize(2048) // 2MB
                        ->nullable()->columnSpan(1),
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
                Split::make([
                    ImageColumn::make('foto_url')
                        ->label('Foto')
                        ->getStateUsing(fn ($record) => $record->foto_url ?: asset('storage/default-icon.png'))
                        ->circular()->alignCenter()
                        ->grow(false),
                    TextColumn::make('name')->label('Nombre')->alignCenter(),
                    TextColumn::make('email')->label('Correo')->alignCenter(),
                    TextColumn::make('role')
                        ->badge()
                        ->icon(function ($state) {
                            if ($state === 'admin') {
                                return 'heroicon-m-shield-check';
                            } elseif ($state === 'editor') {
                                return 'heroicon-o-pencil';
                            } else {
                                return 'heroicon-o-user';
                            }
                        })
                        ->color(function ($state) {
                            if ($state === 'admin') {
                                return 'success';
                            } elseif ($state === 'editor') {
                                return 'warning';
                            } else {
                                return 'primary';
                            }
                        })
                        ->label('Rol')->alignCenter(),
                        // TextColumn::make('faceid_registrado')
                        // ->label('FaceID')
                        // ->badge()
                        // ->icon(fn ($state) => $state ? 'heroicon-o-face-smile' : 'heroicon-o-face-frown')
                        // ->color(fn ($state) => $state ? 'success' : 'danger')
                        // ->formatStateUsing(fn ($state) => $state ? 'OK' : 'No registrado')
                        // ->sortable(),
                ])->from('md'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Action::make('registrar_faceid')
                // ->label('Registrar FaceID')
                // ->icon('heroicon-m-finger-print')
                // ->modalHeading('Registrar FaceID')
                // ->modalSubheading('Conecta tu FaceID o sensor biométrico para registrar este usuario.')
                // ->modalButton('Registrar ahora')
                // ->modalContent(function ($record) {
                //     return view('admin.faceid-register-modal', compact('record'));
                // })
                // ->extraAttributes(['class' => 'text-green-600'])
                // ->visible(fn ($record) => auth()->user()->can('update', $record)) // Opcional: solo administradores
                // ->color('success')
                // ->action(function ($record) {
                //     // $this->dispatchBrowserEvent('faceid-register', [
                //     //     'userId' => $record->id,
                //     // ]);
                // })
                // ->requiresConfirmation() // Opcional: pedir confirmación antes
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
