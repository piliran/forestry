<?php

namespace App\Policies;

use App\Models\Encroached;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EncroachedPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_encroached');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Encroached $encroached): bool
    {
        return $user->hasPermissionTo('view_encroached');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_encroached');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Encroached $encroached): bool
    {
        return $user->hasPermissionTo('update_encroached');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Encroached $encroached): bool
    {
        return $user->hasPermissionTo('delete_encroached');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Encroached $encroached): bool
    {
        return $user->hasPermissionTo('restore_encroached');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Encroached $encroached): bool
    {
        return $user->hasPermissionTo('force_delete_encroached');
    }

    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }
    
        return null;
    }
}
