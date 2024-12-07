<?php

namespace App\Policies;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\PrivilegeChecker;

class StaffPolicy
{
    use PrivilegeChecker;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'view any', new Staff);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Staff $staff): bool
    {
        return $this->hasPrivilege($user->id, 'view', $staff);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'create', new Staff);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Staff $staff): bool
    {
        return $this->hasPrivilege($user->id, 'update', $staff);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Staff $staff): bool
    {
        return $this->hasPrivilege($user->id, 'delete', $staff);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Staff $staff): bool
    {
        return $this->hasPrivilege($user->id, 'restore', $staff);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Staff $staff): bool
    {
        return $this->hasPrivilege($user->id, 'force delete', $staff);
    }

    /**
     * Determine whether the user can perform batch actions.
     */
    public function batchAction(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'batch action', new Staff);
    }

    /**
     * Before hook for additional privilege checks.
     */
    public function before(User $user, string $ability, ?Staff $staff = null): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }

        return null;
    }
}
