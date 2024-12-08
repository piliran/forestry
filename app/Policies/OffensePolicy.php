<?php

namespace App\Policies;

use App\Models\Offense;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\PrivilegeChecker;

class OffensePolicy
{
    use PrivilegeChecker;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasPrivilege($user->id,'view any', $user);

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Offense $offense): bool
    {
        return $this->hasPrivilege($user->id,'view', $offense);

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Offense $offense): bool
    {
        return $this->hasPrivilege($user->id,'create', $offense);

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Offense $offense): bool
    {
        return $this->hasPrivilege($user->id,'update', $offense);

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $this->hasPrivilege($user->id,'delete', new Offense);

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Offense $offense): bool
    {
        return $this->hasPrivilege($user->id,'restore', $offense);

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Offense $offense): bool
    {
        return $this->hasPrivilege($user->id,'force delete', $offense);

    }

    public function batchDelete(User $user, Offense $offense): bool
    {
        return $this->hasPrivilege($user->id,'batch delete', $offense);


    }

    public function before(User $user, string $ability, Offense $offense): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }

        return null;
    }
}
