<?php

namespace App\Policies;

use App\Models\RoleToPermission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RoleToPermissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Check if the user has permission to view any role-to-permission mappings
        return $user->hasPermissionTo('view_any_role_to_permission');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RoleToPermission $roleToPermission): bool
    {
        // Check if the user has permission to view a specific role-to-permission mapping
        return $user->hasPermissionTo('view_role_to_permission');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Check if the user has permission to create a new role-to-permission mapping
        return $user->hasPermissionTo('create_role_to_permission');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RoleToPermission $roleToPermission): bool
    {
        // Check if the user has permission to update a role-to-permission mapping
        return $user->hasPermissionTo('update_role_to_permission');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RoleToPermission $roleToPermission): bool
    {
        // Check if the user has permission to delete the role-to-permission mapping
        return $user->hasPermissionTo('delete_role_to_permission');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RoleToPermission $roleToPermission): bool
    {
        // Check if the user has permission to restore a deleted role-to-permission mapping
        return $user->hasPermissionTo('restore_role_to_permission');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RoleToPermission $roleToPermission): bool
    {
        // Check if the user has permission to permanently delete the role-to-permission mapping
        return $user->hasPermissionTo('force_delete_role_to_permission');
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
        return $user->hasPermissionTo('delete_role_to_permissions');
        
    }
}
