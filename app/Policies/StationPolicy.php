<?php

namespace App\Policies;

use App\Models\Station;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Check if the user has permission to view any Stations
        return $user->hasPermissionTo('view_any_station');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Station $station): bool
    {
        // Check if the user has permission to view a specific Station
        return $user->hasPermissionTo('view_station');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Check if the user has permission to create a new Station
        return $user->hasPermissionTo('create_station');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Station $station): bool
    {
        // Check if the user has permission to update the Station
        // You can also check if the user is the creator of the station, for example
        return $user->hasPermissionTo('update_station');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Station $station): bool
    {
        // Check if the user has permission to delete the Station
        // You could also include checks for specific roles or other conditions
        return $user->hasPermissionTo('delete_station');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Station $station): bool
    {
        // Check if the user has permission to restore a Station
        return $user->hasPermissionTo('restore_station');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Station $station): bool
    {
        // Check if the user has permission to permanently delete a Station
        return $user->hasPermissionTo('force_delete_station');
    }

    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }
    
        return null;
    }
}
