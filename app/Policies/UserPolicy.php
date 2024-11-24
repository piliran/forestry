<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create_user(User $user): bool
    {
        return $user->hasPermissionTo('create_user');
    }

    public function edit_user(User $user): bool
    {
        return $user->hasPermissionTo('edit_user');
    }

    public function delete_user(User $user): bool
    {
        return $user->hasPermissionTo('delete_user');
    }

    public function update(User $user): Response
    {
        return $user ? Response::allow() : Response::denyAsNotFound();                    
                   
    }
  
    public function create(User $user): bool
    {
        return $user->role == 'writer';
    }

    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }
    
        return null;
    }

    public function batchDelete(User $user): bool
    {
        return $user->hasPermissionTo('delete_users');
        
    }
}
