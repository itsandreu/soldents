<?php

namespace App\Policies;

use App\Models\Analogo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AnalogoPolicy
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
    public function view(User $user, Analogo $analogo): bool
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
    public function update(User $user, Analogo $analogo): bool
    {
        if ($user->role === "admin" ) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Analogo $analogo): bool
    {
        if ($user->role === "admin" ) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Analogo $analogo): bool
    {
        if ($user->role === "admin" ) {
            return true;
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Analogo $analogo): bool
    {
        if ($user->role === "admin" ) {
            return true;
        }
    }
}
