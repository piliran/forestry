<?php

namespace App\Policies;

use App\Models\Confiscate;
use App\Models\User;
use App\PrivilegeChecker;

class ConfiscatePolicy
{
    use PrivilegeChecker;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'view any', new Confiscate());
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Confiscate $confiscate): bool
    {
        return $this->hasPrivilege($user->id, 'view', $confiscate);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'create', new Confiscate());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Confiscate $confiscate): bool
    {
        return $this->hasPrivilege($user->id, 'update', $confiscate);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Confiscate $confiscate): bool
    {
        return $this->hasPrivilege($user->id, 'delete', $confiscate);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Confiscate $confiscate): bool
    {
        return $this->hasPrivilege($user->id, 'restore', $confiscate);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Confiscate $confiscate): bool
    {
        return $this->hasPrivilege($user->id, 'force delete', $confiscate);
    }

    /**
     * Determine whether the user can delete multiple models.
     */
    public function batchDelete(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'batch delete', new Confiscate());
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
