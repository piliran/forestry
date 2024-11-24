<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Auth\Access\Response;

class UserRolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Users with the 'view_any_user_role' permission can view any user role
        return $user->hasPermissionTo('view_any_user_role');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserRole $userRole): bool
    {
        // Users with the 'view_user_role' permission can view any role
        // You can add an additional check to ensure the user is the owner or has specific access
        return $user->hasPermissionTo('view_user_role');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Users with the 'create_user_role' permission can create a new user role
        return $user->hasPermissionTo('create_user_role');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserRole $userRole): bool
    {
        // Users with the 'update_user_role' permission can update the role
        // You can also check if the user has a higher role or is an admin
        return $user->hasPermissionTo('update_user_role');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserRole $userRole): bool
    {
        // Users with the 'delete_user_role' permission can delete the user role
        // You can also restrict deletion to admins or users with a certain role
        return $user->hasPermissionTo('delete_user_role');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserRole $userRole): bool
    {
        // Users with the 'restore_user_role' permission can restore deleted roles
        // You can also check if the user is an admin or has the necessary role
        return $user->hasPermissionTo('restore_user_role');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserRole $userRole): bool
    {
        // Users with the 'force_delete_user_role' permission can permanently delete the role
        // You can also check if the user is an admin or the user is deleting their own role
        return $user->hasPermissionTo('force_delete_user_role');
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
        return $user->hasPermissionTo('delete_cuser_roles');
        
    }
}
