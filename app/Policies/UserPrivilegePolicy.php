<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserPrivilege;
use App\PrivilegeChecker;

class UserPrivilegePolicy
{
    use PrivilegeChecker;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'view any', new UserPrivilege);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserPrivilege $userPrivilege): bool
    {
        return $this->hasPrivilege($user->id, 'view', $userPrivilege);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'create', new UserPrivilege);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserPrivilege $userPrivilege): bool
    {
        return $this->hasPrivilege($user->id, 'update', $userPrivilege);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserPrivilege $userPrivilege): bool
    {
        return $this->hasPrivilege($user->id, 'delete', $userPrivilege);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserPrivilege $userPrivilege): bool
    {
        return $this->hasPrivilege($user->id, 'restore', $userPrivilege);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserPrivilege $userPrivilege): bool
    {
        return $this->hasPrivilege($user->id, 'force delete', $userPrivilege);
    }

    /**
     * Before hook for additional privilege checks.
     */
    public function before(User $user, string $ability, ?UserPrivilege $userPrivilege = null): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }

        return null;
    }
}
