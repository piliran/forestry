<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeamPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Check if the user has permission to view any teams
        return $user->hasPermissionTo('view_any_team');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Team $team): bool
    {
        // Check if the user has permission to view a specific team
        // You can also check if the user is a member of the team or if they are the team owner
        return $user->hasPermissionTo('view_team') || $user->id === $team->owner_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Check if the user has permission to create a new team
        return $user->hasPermissionTo('create_team');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Team $team): bool
    {
        // Check if the user has permission to update the team
        // You can also check if the user is the owner of the team or has a special role
        return $user->hasPermissionTo('update_team') || $user->id === $team->owner_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Team $team): bool
    {
        // Check if the user has permission to delete the team
        // You can also check if the user is the owner or has a special role
        return $user->hasPermissionTo('delete_team') || $user->id === $team->owner_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Team $team): bool
    {
        // Check if the user has permission to restore a team
        // You can also check if the user is the owner or has a special role
        return $user->hasPermissionTo('restore_team') || $user->id === $team->owner_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Team $team): bool
    {
        // Check if the user has permission to permanently delete a team
        // You can also check if the user is the owner or has a special role
        return $user->hasPermissionTo('force_delete_team') || $user->id === $team->owner_id;
    }

    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }
    
        return null;
    }
}
