<?php

namespace App\Policies;

use App\Models\RoleToPrivilege;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\PrivilegeChecker;

class RoleToPrivilegePolicy
{
    use PrivilegeChecker;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'view any', new RoleToPrivilege);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RoleToPrivilege $roleToPrivilege): bool
    {
        return $this->hasPrivilege($user->id, 'view', $roleToPrivilege);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'create', new RoleToPrivilege);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RoleToPrivilege $roleToPrivilege): bool
    {
        return $this->hasPrivilege($user->id, 'update', $roleToPrivilege);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RoleToPrivilege $roleToPrivilege): bool
    {
        return $this->hasPrivilege($user->id, 'delete', $roleToPrivilege);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RoleToPrivilege $roleToPrivilege): bool
    {
        return $this->hasPrivilege($user->id, 'restore', $roleToPrivilege);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RoleToPrivilege $roleToPrivilege): bool
    {
        return $this->hasPrivilege($user->id, 'force delete', $roleToPrivilege);
    }

    /**
     * Determine whether the user can batch delete models.
     */
    public function batchDelete(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'batch delete', new RoleToPrivilege);
    }

    /**
     * Before hook for additional privilege checks.
     */
    public function before(User $user, string $ability, ?RoleToPrivilege $roleToPrivilege = null): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }

        return null;
    }
}
