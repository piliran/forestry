<?php

namespace App\Policies;

use App\Models\Area;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AreaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Check if the user has permission to view any area
        return $user->hasPermissionTo('view_any_area');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Area $area): bool
    {
        // Check if the user has permission to view the specific area
        return $user->hasPermissionTo('view_area') || $user->id === $area->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Check if the user has permission to create an area
        return $user->hasPermissionTo('create_area');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Area $area): bool
    {
        // Check if the user has permission to update the area or if the user owns the area
        return $user->hasPermissionTo('update_area') || $user->id === $area->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Area $area): bool
    {
        // Check if the user has permission to delete the area or if the user owns the area
        return $user->hasPermissionTo('delete_area') || $user->id === $area->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Area $area): bool
    {
        // Check if the user has permission to restore the area or if the user owns the area
        return $user->hasPermissionTo('restore_area') || $user->id === $area->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Area $area): bool
    {
        // Check if the user has permission to permanently delete the area or if the user owns the area
        return $user->hasPermissionTo('force_delete_area') || $user->id === $area->user_id;
    }

    /**
     * This method is called before any other policy method.
     * It allows administrators to bypass all checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        // If the user is an administrator, they can perform any action
        if ($user->isAdministrator()) {
            return true; // Allow all actions for administrators
        }
    
        // Otherwise, proceed with the usual checks
        return null;
    }
}
