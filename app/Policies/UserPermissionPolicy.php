<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Auth\Access\Response;

class UserPermissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Check if the user has permission to view any UserPermissions
        // You can check for an admin role or a specific permission
        return $user->hasPermissionTo('view_any_user_permission');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserPermission $userPermission): bool
    {
        // Check if the user has permission to view a specific UserPermission
        // You can add more logic to check if the user is the one who owns the permission or has a specific role
        return $user->hasPermissionTo('view_user_permission');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Check if the user has permission to create a UserPermission
        return $user->hasPermissionTo('create_user_permission');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserPermission $userPermission): bool
    {
        // Check if the user has permission to update the UserPermission
        // You can also check if the user is the owner or has an admin role
        return $user->hasPermissionTo('update_user_permission');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserPermission $userPermission): bool
    {
        // Check if the user has permission to delete the UserPermission
        // You can also check if the user is an admin or the user who owns the permission
        return $user->hasPermissionTo('delete_user_permission');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserPermission $userPermission): bool
    {
        // Check if the user has permission to restore the UserPermission
        // You can also check if the user is an admin or the owner of the permission
        return $user->hasPermissionTo('restore_user_permission');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserPermission $userPermission): bool
    {
        // Check if the user has permission to permanently delete the UserPermission
        // You can also check if the user is an admin or the owner of the permission
        return $user->hasPermissionTo('force_delete_user_permission');
    }

    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }
    
        return null;
    }
}
