<?php

namespace App\Policies;

use App\Models\Arrest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArrestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_arrests');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Arrest $arrest): bool
    {
        return $user->hasPermissionTo('view_arrest');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_arrest');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Arrest $arrest): bool
    {
        return $user->hasPermissionTo('update_arrest');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Arrest $arrest): bool
    {
        return $user->hasPermissionTo('delete_arrest');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Arrest $arrest): bool
    {
        return $user->hasPermissionTo('restore_arrest');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Arrest $arrest): bool
    {
        return $user->hasPermissionTo('force_delete_arrest');
    }

    public function batchDelete(User $user): bool
    {
        return $user->hasPermissionTo('delete_arrests');
        
    }


    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }
    
        return null;
    }
}
