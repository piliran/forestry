<?php

namespace App\Policies;

use App\Models\Crime;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CrimePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_crimes');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Crime $crime): bool
    {
        return $user->hasPermissionTo('view_crime');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_crime');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Crime $crime): bool
    {
        return $user->hasPermissionTo('update_crime');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Crime $crime): bool
    {
        return $user->hasPermissionTo('delete_crime');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Crime $crime): bool
    {
        return $user->hasPermissionTo('restore_crime');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Crime $crime): bool
    {
        return $user->hasPermissionTo('force_delete_crime');
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
        return $user->hasPermissionTo('delete_crimes');
        
    }
}
