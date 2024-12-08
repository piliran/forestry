<?php

namespace App\Policies;

use App\Models\OperationToFunder;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\PrivilegeChecker;

class OperationTofunderPolicy
{
    use PrivilegeChecker;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'view any', new OperationToFunder);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OperationToFunder $operationToFunder): bool
    {
        return $this->hasPrivilege($user->id, 'view', $operationToFunder);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'create', new OperationToFunder);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OperationToFunder $operationToFunder): bool
    {
        return $this->hasPrivilege($user->id, 'update', $operationToFunder);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OperationToFunder $operationToFunder): bool
    {
        return $this->hasPrivilege($user->id, 'delete', $operationToFunder);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OperationToFunder $operationToFunder): bool
    {
        return $this->hasPrivilege($user->id, 'restore', $operationToFunder);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OperationToFunder $operationToFunder): bool
    {
        return $this->hasPrivilege($user->id, 'force delete', $operationToFunder);
    }

    /**
     * Determine whether the user can batch delete models.
     */
    public function batchDelete(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'batch delete', new OperationToFunder);
    }

    /**
     * Before hook for additional privilege checks.
     */
    public function before(User $user, string $ability, ?OperationToFunder $operationToFunder = null): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }

        return null;
    }
}
