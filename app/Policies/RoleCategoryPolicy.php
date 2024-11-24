<?php

namespace App\Policies;

use App\Models\RoleCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RoleCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Check if the user has permission to view any RoleCategory
        return $user->hasPermissionTo('view_any_role_category');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RoleCategory $roleCategory): bool
    {
        // Check if the user has permission to view the specific RoleCategory
        return $user->hasPermissionTo('view_role_category');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Check if the user has permission to create a RoleCategory
        return $user->hasPermissionTo('create_role_category');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RoleCategory $roleCategory): bool
    {
        // Check if the user has permission to update the RoleCategory
        return $user->hasPermissionTo('update_role_category');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RoleCategory $roleCategory): bool
    {
        // Check if the user has permission to delete the RoleCategory
        return $user->hasPermissionTo('delete_role_category');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RoleCategory $roleCategory): bool
    {
        // Check if the user has permission to restore the RoleCategory
        return $user->hasPermissionTo('restore_role_category');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RoleCategory $roleCategory): bool
    {
        // Check if the user has permission to permanently delete the RoleCategory
        return $user->hasPermissionTo('force_delete_role_category');
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
        return $user->hasPermissionTo('delete_role_categories');
        
    }
}
