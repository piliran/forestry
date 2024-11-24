<?php

namespace App\Policies;

use App\Models\RouteType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RouteTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Check if the user has permission to view any RouteType
        return $user->hasPermissionTo('view_any_route_type');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RouteType $routeType): bool
    {
        // Check if the user has permission to view a specific RouteType
        return $user->hasPermissionTo('view_route_type');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Check if the user has permission to create a RouteType
        return $user->hasPermissionTo('create_route_type');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RouteType $routeType): bool
    {
        // Check if the user has permission to update a RouteType, or if they are the creator of the RouteType
        return $user->hasPermissionTo('update_route_type');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RouteType $routeType): bool
    {
        // Check if the user has permission to delete the RouteType, or if they are the creator of the RouteType
        return $user->hasPermissionTo('delete_route_type');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RouteType $routeType): bool
    {
        // Check if the user has permission to restore the RouteType
        return $user->hasPermissionTo('restore_route_type');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RouteType $routeType): bool
    {
        // Check if the user has permission to permanently delete the RouteType
        return $user->hasPermissionTo('force_delete_route_type');
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
        return $user->hasPermissionTo('delete_route_types');
        
    }
}
