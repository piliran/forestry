<?php

namespace App\Policies;

use App\Models\Confiscate;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ConfiscatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_confiscates');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Confiscate $confiscate): bool
    {
        return $user->hasPermissionTo('view_confiscate');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_confiscate');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Confiscate $confiscate): bool
    {
        return $user->hasPermissionTo('update_confiscate');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Confiscate $confiscate): bool
    {
        return $user->hasPermissionTo('delete_confiscate');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Confiscate $confiscate): bool
    {
        return $user->hasPermissionTo('restore_confiscate');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Confiscate $confiscate): bool
    {
        return $user->hasPermissionTo('force_delete_confiscate');
    }

    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }
    
        return null;
    }

    public function batchDelete(User $user): bool
    {
        return $user->hasPermissionTo('delete_confiscates');
        
    }

}
