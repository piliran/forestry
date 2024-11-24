<?php

namespace App\Policies;

use App\Models\Route;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RoutePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Check if the user has permission to view any routes
        return $user->hasPermissionTo('view_any_route');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Route $route): bool
    {
        // Check if the user has permission to view a specific route
        return $user->hasPermissionTo('view_route');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Check if the user has permission to create a new route
        return $user->hasPermissionTo('create_route');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Route $route): bool
    {
        // Check if the user has permission to update a route, or if they are the creator of the route
        return $user->hasPermissionTo('update_route') ;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Route $route): bool
    {
        // Check if the user has permission to delete the route, or if they are the creator of the route
        return $user->hasPermissionTo('delete_route');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Route $route): bool
    {
        // Check if the user has permission to restore the route
        return $user->hasPermissionTo('restore_route');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Route $route): bool
    {
        // Check if the user has permission to permanently delete the route
        return $user->hasPermissionTo('force_delete_route');
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
        return $user->hasPermissionTo('delete_routes');
        
    }
}
