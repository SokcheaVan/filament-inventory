<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return !$user->role || in_array('user-read', $user->role->permissions);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return !$user->role || in_array('user-read', $user->role->permissions);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return !$user->role || in_array('user-create', $user->role->permissions);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return !$user->role || in_array('user-update', $user->role->permissions);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return !$user->role || in_array('user-delete', $user->role->permissions);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restoreAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): bool
    {
        return false;
    }
}
