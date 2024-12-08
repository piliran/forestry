<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\PrivilegeChecker;
class UserPolicy
{
    use PrivilegeChecker;
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }


    public function create(User $user): bool
    {
        return $this->hasPrivilege($user->id,'create', $user);
        // return $user->hasPermissionTo('create_user');
    }

    public function edit(User $user): bool
    {
        // return $user->hasPermissionTo('edit_user');
        return $this->hasPrivilege($user->id,'edit', $user);

    }

    public function delete(User $user): bool
    {
        return $this->hasPrivilege($user->id,'delete', $user);
        // return $user->hasPermissionTo('delete_user');
    }

    public function update(User $user): bool
    {
        // return $user ? Response::allow() : Response::denyAsNotFound();
        return $this->hasPrivilege($user->id,'update', $user);


    }

    public function batchDelete(User $user): bool
    {
        return $this->hasPrivilege($user->id,'batch delete', $user);


    }

    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }

        return null;
    }


}
