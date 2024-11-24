<?php

namespace App\Policies;

use App\Models\SpeciesCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SpeciesCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Check if the user has permission to view any SpeciesCategory
        return $user->hasPermissionTo('view_any_species_category');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SpeciesCategory $speciesCategory): bool
    {
        // Check if the user has permission to view a specific SpeciesCategory
        // You can also add additional checks, for example, if the user is the creator of the category
        return $user->hasPermissionTo('view_species_category');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Check if the user has permission to create a SpeciesCategory
        return $user->hasPermissionTo('create_species_category');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SpeciesCategory $speciesCategory): bool
    {
        // Check if the user has permission to update the SpeciesCategory
        // You can also add additional checks, such as ensuring the user is the creator
        return $user->hasPermissionTo('update_species_category');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SpeciesCategory $speciesCategory): bool
    {
        // Check if the user has permission to delete the SpeciesCategory
        // You can also add additional checks, such as ensuring the user is the creator
        return $user->hasPermissionTo('delete_species_category');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SpeciesCategory $speciesCategory): bool
    {
        // Check if the user has permission to restore the SpeciesCategory
        return $user->hasPermissionTo('restore_species_category');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SpeciesCategory $speciesCategory): bool
    {
        // Check if the user has permission to permanently delete the SpeciesCategory
        return $user->hasPermissionTo('force_delete_species_category');
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
        return $user->hasPermissionTo('delete_species_categories');
        
    }
}
