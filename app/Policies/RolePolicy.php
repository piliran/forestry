<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use App\PrivilegeChecker;

class RolePolicy
{
    use PrivilegeChecker;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'view any', new Role());
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        return $this->hasPrivilege($user->id, 'view', $role);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'create', new Role());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        return $this->hasPrivilege($user->id, 'update', $role);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        return $this->hasPrivilege($user->id, 'delete', $role);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        return $this->hasPrivilege($user->id, 'restore', $role);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return $this->hasPrivilege($user->id, 'force delete', $role);
    }

    /**
     * Determine whether the user can delete multiple models.
     */
    public function batchDelete(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'batch delete', new Role());
    }

    /**
     * This method is called before any other policy method.
     * It allows administrators to bypass all checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }

        return null;
    }
}
