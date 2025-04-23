<?php

namespace App\Policies;

use App\Models\Fresa;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FresaPolicy
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
    public function view(User $user, Fresa $fresa): bool
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
    public function update(User $user, Fresa $fresa): bool
    {
        if ($user->role === "admin" ) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Fresa $fresa): bool
    {
        if ($user->role === "admin" ) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Fresa $fresa): bool
    {
        if ($user->role === "admin" ) {
            return true;
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Fresa $fresa): bool
    {
        if ($user->role === "admin" ) {
            return true;
        }
    }
}
