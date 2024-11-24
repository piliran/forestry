<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Zone;
use Illuminate\Auth\Access\Response;

class ZonePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Users with the 'view_any_zone' permission can view all zones
        return $user->hasPermissionTo('view_any_zone');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Zone $zone): bool
    {
        // Users with the 'view_zone' permission can view the zone
        // You can add additional checks to ensure the user is the owner of the zone or has special access
        return $user->hasPermissionTo('view_zone');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Users with the 'create_zone' permission can create a zone
        return $user->hasPermissionTo('create_zone');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Zone $zone): bool
    {
        // Users with the 'update_zone' permission can update a zone
        // You can check if the user is the owner or if they have a higher-level role
        return $user->hasPermissionTo('update_zone');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Zone $zone): bool
    {
        // Users with the 'delete_zone' permission can delete a zone
        // You can check if the user is the owner or has admin privileges
        return $user->hasPermissionTo('delete_zone');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Zone $zone): bool
    {
        // Users with the 'restore_zone' permission can restore a deleted zone
        // You can also check if the user is an admin or has special privileges
        return $user->hasPermissionTo('restore_zone');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Zone $zone): bool
    {
        // Users with the 'force_delete_zone' permission can permanently delete a zone
        // You can also check if the user is an admin or the owner of the zone
        return $user->hasPermissionTo('force_delete_zone');
    }

    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }
    
        return null;
    }
}
