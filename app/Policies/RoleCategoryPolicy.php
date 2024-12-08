<?php

namespace App\Policies;

use App\Models\RoleCategory;
use App\Models\User;
use App\PrivilegeChecker;

class RoleCategoryPolicy
{
    use PrivilegeChecker;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'view any', new RoleCategory());
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RoleCategory $roleCategory): bool
    {
        return $this->hasPrivilege($user->id, 'view', $roleCategory);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'create', new RoleCategory());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RoleCategory $roleCategory): bool
    {
        return $this->hasPrivilege($user->id, 'update', $roleCategory);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RoleCategory $roleCategory): bool
    {
        return $this->hasPrivilege($user->id, 'delete', $roleCategory);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RoleCategory $roleCategory): bool
    {
        return $this->hasPrivilege($user->id, 'restore', $roleCategory);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RoleCategory $roleCategory): bool
    {
        return $this->hasPrivilege($user->id, 'force delete', $roleCategory);
    }

    /**
     * Determine whether the user can delete multiple models.
     */
    public function batchDelete(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'batch delete', new RoleCategory());
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
