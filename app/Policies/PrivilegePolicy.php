<?php

namespace App\Policies;

use App\Models\Privilege;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\PrivilegeChecker;

class PrivilegePolicy
{
    use PrivilegeChecker;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'view any', new Privilege);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Privilege $privilege): bool
    {
        return $this->hasPrivilege($user->id, 'view', $privilege);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'create', new Privilege);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Privilege $privilege): bool
    {
        return $this->hasPrivilege($user->id, 'update', $privilege);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Privilege $privilege): bool
    {
        return $this->hasPrivilege($user->id, 'delete', $privilege);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Privilege $privilege): bool
    {
        return $this->hasPrivilege($user->id, 'restore', $privilege);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Privilege $privilege): bool
    {
        return $this->hasPrivilege($user->id, 'force delete', $privilege);
    }

    /**
     * Determine whether the user can batch delete models.
     */
    public function batchDelete(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'batch delete', new Privilege);
    }

    /**
     * Before hook for additional privilege checks.
     */
    public function before(User $user, string $ability, ?Privilege $privilege = null): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }

        return null;
    }
}
