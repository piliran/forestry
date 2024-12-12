<?php

namespace App\Policies;

use App\Models\Table;
use App\Models\User;
use Illuminate\Auth\Access\Response;

use App\PrivilegeChecker;
class TablePolicy
{
    use PrivilegeChecker;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'view any', new Table());
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Table $team): bool
    {
        return $this->hasPrivilege($user->id, 'view', $team);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'create', new Table());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Table $team): bool
    {
        return $this->hasPrivilege($user->id, 'update', $team);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Table $team): bool
    {
        return $this->hasPrivilege($user->id, 'delete', $team);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Table $team): bool
    {
        return $this->hasPrivilege($user->id, 'restore', $team);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Table $team): bool
    {
        return $this->hasPrivilege($user->id, 'force delete', $team);
    }

    /**
     * Determine whether the user can delete multiple models.
     */
    public function batchDelete(User $user): bool
    {
        return $this->hasPrivilege($user->id, 'batch delete', new Table());
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
