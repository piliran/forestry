<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SchedulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Check if the user has permission to view any Schedule
        return $user->hasPermissionTo('view_any_schedule');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Schedule $schedule): bool
    {
        // Check if the user has permission to view a specific Schedule, 
        // or if they are the creator of the schedule
        return $user->hasPermissionTo('view_schedule') || $user->id === $schedule->created_by;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Check if the user has permission to create a Schedule
        return $user->hasPermissionTo('create_schedule');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Schedule $schedule): bool
    {
        // Check if the user has permission to update the Schedule,
        // or if they are the creator of the Schedule
        return $user->hasPermissionTo('update_schedule') || $user->id === $schedule->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Schedule $schedule): bool
    {
        // Check if the user has permission to delete the Schedule,
        // or if they are the creator of the Schedule
        return $user->hasPermissionTo('delete_schedule') || $user->id === $schedule->created_by;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Schedule $schedule): bool
    {
        // Check if the user has permission to restore the Schedule
        return $user->hasPermissionTo('restore_schedule');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Schedule $schedule): bool
    {
        // Check if the user has permission to permanently delete the Schedule
        return $user->hasPermissionTo('force_delete_schedule');
    }

    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }
    
        return null;
    }
}
