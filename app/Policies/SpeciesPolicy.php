<?php

namespace App\Policies;

use App\Models\Species;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SpeciesPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Check if the user has permission to view any Species
        return $user->hasPermissionTo('view_any_species');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Species $species): bool
    {
        // Check if the user has permission to view a specific Species
        // You can also add additional checks, such as ensuring the user is the creator of the species
        return $user->hasPermissionTo('view_species');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Check if the user has permission to create a new Species
        return $user->hasPermissionTo('create_species');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Species $species): bool
    {
        // Check if the user has permission to update the Species
        // You can also add additional checks, such as ensuring the user is the creator
        return $user->hasPermissionTo('update_species');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Species $species): bool
    {
        // Check if the user has permission to delete the Species
        // You can also add additional checks, such as ensuring the user is the creator
        return $user->hasPermissionTo('delete_species');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Species $species): bool
    {
        // Check if the user has permission to restore the Species
        return $user->hasPermissionTo('restore_species');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Species $species): bool
    {
        // Check if the user has permission to permanently delete the Species
        return $user->hasPermissionTo('force_delete_species');
    }

    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }
    
        return null;
    }
}
