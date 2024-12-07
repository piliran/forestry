<?php

namespace App\Policies;

use App\Models\StationToOperation;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\PrivilegeChecker;

class StationToOperationPolicy
{
    use PrivilegeChecker;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'view any', new StationToOperation);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StationToOperation $stationToOperation): bool
    {
        return $this->hasPrivilege($user->id, 'view', $stationToOperation);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'create', new StationToOperation);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StationToOperation $stationToOperation): bool
    {
        return $this->hasPrivilege($user->id, 'update', $stationToOperation);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StationToOperation $stationToOperation): bool
    {
        return $this->hasPrivilege($user->id, 'delete', $stationToOperation);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StationToOperation $stationToOperation): bool
    {
        return $this->hasPrivilege($user->id, 'restore', $stationToOperation);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StationToOperation $stationToOperation): bool
    {
        return $this->hasPrivilege($user->id, 'force delete', $stationToOperation);
    }

    /**
     * Determine whether the user can perform batch actions.
     */
    public function batchAction(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'batch action', new StationToOperation);
    }

    /**
     * Before hook for additional privilege checks.
     */
    public function before(User $user, string $ability, ?StationToOperation $stationToOperation = null): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }

        return null;
    }
}
