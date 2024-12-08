<?php

namespace App\Policies;

use App\Models\OperationToTeam;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\PrivilegeChecker;

class OperationToTeamPolicy
{
    use PrivilegeChecker;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'view any', new OperationToTeam);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OperationToTeam $operationToTeam): bool
    {
        return $this->hasPrivilege($user->id, 'view', $operationToTeam);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'create', new OperationToTeam);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OperationToTeam $operationToTeam): bool
    {
        return $this->hasPrivilege($user->id, 'update', $operationToTeam);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OperationToTeam $operationToTeam): bool
    {
        return $this->hasPrivilege($user->id, 'delete', $operationToTeam);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OperationToTeam $operationToTeam): bool
    {
        return $this->hasPrivilege($user->id, 'restore', $operationToTeam);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OperationToTeam $operationToTeam): bool
    {
        return $this->hasPrivilege($user->id, 'force delete', $operationToTeam);
    }

    /**
     * Determine whether the user can batch delete models.
     */
    public function batchDelete(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'batch delete', new OperationToTeam);
    }

    /**
     * Before hook for additional privilege checks.
     */
    public function before(User $user, string $ability, ?OperationToTeam $operationToTeam = null): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }

        return null;
    }
}
