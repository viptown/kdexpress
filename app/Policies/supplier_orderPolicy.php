<?php

namespace App\Policies;

use App\Models\BlStatus;
use App\Models\User;
use App\Models\supplier_order;
use Illuminate\Auth\Access\Response;

class supplier_orderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor() || $user->isUser() || $user->isApiUser();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, supplier_order $supplierOrder): bool
    {
        return $user->isAdmin() || $user->isEditor();
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
    public function update(User $user, supplier_order $supplierOrder): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, supplier_order $supplierOrder): bool
    {
        return $user->isAdmin() || $user->isEditor();
        //return ($supplierOrder->bl_status_id == BlStatus::where('name', '접수')->first()->id) ? true : false;
    }

    /**
     * Determine whether the user can bulk delete the models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, supplier_order $supplierOrder): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    /**
     * Determine whether the user can bulk restore the models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, supplier_order $supplierOrder): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    /**
     * Determine whether the user can permanently bulk delete the models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }
}
