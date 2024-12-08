<?php

namespace App\Policies;

use App\Models\SuspectToOperation;
use App\Models\User;
use App\PrivilegeChecker;

class SuspectToOperationPolicy
{
    use PrivilegeChecker;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'view any', new SuspectToOperation);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SuspectToOperation $suspectToOperation): bool
    {
        return $this->hasPrivilege($user->id, 'view', $suspectToOperation);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'create', new SuspectToOperation);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SuspectToOperation $suspectToOperation): bool
    {
        return $this->hasPrivilege($user->id, 'update', $suspectToOperation);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SuspectToOperation $suspectToOperation): bool
    {
        return $this->hasPrivilege($user->id, 'delete', $suspectToOperation);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SuspectToOperation $suspectToOperation): bool
    {
        return $this->hasPrivilege($user->id, 'restore', $suspectToOperation);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SuspectToOperation $suspectToOperation): bool
    {
        return $this->hasPrivilege($user->id, 'force delete', $suspectToOperation);
    }

    /**
     * Before hook for additional privilege checks.
     */
    public function before(User $user, string $ability, ?SuspectToOperation $suspectToOperation = null): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }

        return null;
    }
}
