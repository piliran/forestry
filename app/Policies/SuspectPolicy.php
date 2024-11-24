<?php

namespace App\Policies;

use App\Models\Suspect;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SuspectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_suspects');
        
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Suspect $suspect): bool
    {
        return $user->hasPermissionTo('view_suspect');

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_suspect');

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Suspect $suspect): bool
    {
        return $user->hasPermissionTo('update_suspect');
        
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Suspect $suspect): bool
    {
        return $user->hasPermissionTo('delete_suspect');
        
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Suspect $suspect): bool
    {
        return $user->hasPermissionTo('restore_suspect');
        
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Suspect $suspect): bool
    {
        return $user->hasPermissionTo('force_delete_suspect');
        
    }

    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }
    
        return null;
    }
}
