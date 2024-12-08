<?php

namespace App\Policies;

use App\Models\StaffToStation;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\PrivilegeChecker;

class StaffToStationPolicy
{
    use PrivilegeChecker;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'view any', new StaffToStation);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StaffToStation $staffToStation): bool
    {
        return $this->hasPrivilege($user->id, 'view', $staffToStation);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'create', new StaffToStation);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StaffToStation $staffToStation): bool
    {
        return $this->hasPrivilege($user->id, 'update', $staffToStation);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StaffToStation $staffToStation): bool
    {
        return $this->hasPrivilege($user->id, 'delete', $staffToStation);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StaffToStation $staffToStation): bool
    {
        return $this->hasPrivilege($user->id, 'restore', $staffToStation);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StaffToStation $staffToStation): bool
    {
        return $this->hasPrivilege($user->id, 'force delete', $staffToStation);
    }

    /**
     * Determine whether the user can perform batch actions.
     */
    public function batchAction(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'batch action', new StaffToStation);
    }

    /**
     * Before hook for additional privilege checks.
     */
    public function before(User $user, string $ability, ?StaffToStation $staffToStation = null): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }

        return null;
    }
}
