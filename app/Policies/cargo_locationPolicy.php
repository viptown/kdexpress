<?php

namespace App\Policies;

use App\Models\User;
use App\Models\cargo_location;
use Illuminate\Auth\Access\Response;

class cargo_locationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor() || $user->isUser();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, cargo_location $cargoLocation): bool
    {
        return $user->isAdmin() || $user->isEditor() || $user->isUser();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, cargo_location $cargoLocation): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, cargo_location $cargoLocation): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, cargo_location $cargoLocation): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    public function restoreAny(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, cargo_location $cargoLocation): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
