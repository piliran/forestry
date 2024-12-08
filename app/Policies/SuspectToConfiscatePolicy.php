<?php

namespace App\Policies;

use App\Models\SuspectToConfiscate;
use App\Models\User;
use App\PrivilegeChecker;

class SuspectToConfiscatePolicy
{
    use PrivilegeChecker;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'view any', new SuspectToConfiscate);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SuspectToConfiscate $suspectToConfiscate): bool
    {
        return $this->hasPrivilege($user->id, 'view', $suspectToConfiscate);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'create', new SuspectToConfiscate);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SuspectToConfiscate $suspectToConfiscate): bool
    {
        return $this->hasPrivilege($user->id, 'update', $suspectToConfiscate);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SuspectToConfiscate $suspectToConfiscate): bool
    {
        return $this->hasPrivilege($user->id, 'delete', $suspectToConfiscate);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SuspectToConfiscate $suspectToConfiscate): bool
    {
        return $this->hasPrivilege($user->id, 'restore', $suspectToConfiscate);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SuspectToConfiscate $suspectToConfiscate): bool
    {
        return $this->hasPrivilege($user->id, 'force delete', $suspectToConfiscate);
    }

    /**
     * Determine whether the user can perform batch actions.
     */
    public function batchAction(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'batch action', new SuspectToConfiscate);
    }

    /**
     * Before hook for additional privilege checks.
     */
    public function before(User $user, string $ability, ?SuspectToConfiscate $suspectToConfiscate = null): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }

        return null;
    }
}
