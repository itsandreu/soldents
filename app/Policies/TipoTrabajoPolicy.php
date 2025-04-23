<?php

namespace App\Policies;

use App\Models\TipoTrabajo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TipoTrabajoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TipoTrabajo $tipoTrabajo): bool
    {
        if ($user->role === "admin" ) {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->role === "admin" ) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TipoTrabajo $tipoTrabajo): bool
    {
        if ($user->role === "admin" ) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TipoTrabajo $tipoTrabajo): bool
    {
        if ($user->role === "admin" ) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TipoTrabajo $tipoTrabajo): bool
    {
        if ($user->role === "admin" ) {
            return true;
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TipoTrabajo $tipoTrabajo): bool
    {
        if ($user->role === "admin" ) {
            return true;
        }
    }
}
