<?php

namespace App\Policies;

use App\Models\SuspectToOffense;
use App\Models\User;
use App\PrivilegeChecker;

class SuspectToOffensePolicy
{
    use PrivilegeChecker;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'view any', new SuspectToOffense);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SuspectToOffense $suspectToOffense): bool
    {
        return $this->hasPrivilege($user->id, 'view', $suspectToOffense);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'create', new SuspectToOffense);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SuspectToOffense $suspectToOffense): bool
    {
        return $this->hasPrivilege($user->id, 'update', $suspectToOffense);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SuspectToOffense $suspectToOffense): bool
    {
        return $this->hasPrivilege($user->id, 'delete', $suspectToOffense);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SuspectToOffense $suspectToOffense): bool
    {
        return $this->hasPrivilege($user->id, 'restore', $suspectToOffense);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SuspectToOffense $suspectToOffense): bool
    {
        return $this->hasPrivilege($user->id, 'force delete', $suspectToOffense);
    }

    /**
     * Before hook for additional privilege checks.
     */
    public function before(User $user, string $ability, ?SuspectToOffense $suspectToOffense = null): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }

        return null;
    }
}
