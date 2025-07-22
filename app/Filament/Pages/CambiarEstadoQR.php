<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Trabajo;
use Illuminate\Http\Request;

class CambiarEstadoQR extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.cambiar-estado-q-r';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'qr/estado/{token}'; // Slug personalizado

    protected static ?string $title = '';

    protected static bool $isPublic = true; // ⚡ HACER PÚBLICA

    protected static ?string $authGuard = null;

    public $trabajo;
    public $estados;

    protected static function shouldRedirectToLogin(): bool
    {
        return false;
    }

    public static function route(): string
    {
        return '/qr/estado/{token}';
    }

    public function mount(Request $request, $token)
    {
        $this->estados = \App\Models\Estado::whereIn('id', [1, 2, 3, 4, 5])->get(); // o todos los que necesites
        $this->trabajo = Trabajo::where('qr_token', $token)->firstOrFail();
    }

    public function cambiarEstado($nuevoEstado)
    {
        if ($nuevoEstado >= 1 && $nuevoEstado <= 5) {
            $this->trabajo->estado_id = $nuevoEstado;
            $this->trabajo->save();
            session()->flash('success', 'Estado actualizado correctamente.');
        }
    }
}
