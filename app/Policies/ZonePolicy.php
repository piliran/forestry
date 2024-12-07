<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Zone;
use App\PrivilegeChecker;

class ZonePolicy
{
    use PrivilegeChecker;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'view any', new Zone());
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Zone $zone): bool
    {
        return $this->hasPrivilege($user->id, 'view', $zone);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'create', new Zone());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Zone $zone): bool
    {
        return $this->hasPrivilege($user->id, 'update', $zone);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Zone $zone): bool
    {
        return $this->hasPrivilege($user->id, 'delete', $zone);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Zone $zone): bool
    {
        return $this->hasPrivilege($user->id, 'restore', $zone);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Zone $zone): bool
    {
        return $this->hasPrivilege($user->id, 'force delete', $zone);
    }

    /**
     * Determine whether the user can delete multiple models.
     */
    public function batchDelete(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'batch delete', new Zone());
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
