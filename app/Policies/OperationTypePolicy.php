<?php

namespace App\Policies;

use App\Models\OperationType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OperationTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_operation_type');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OperationType $operationType): bool
    {
        // User can view an operation type if they have the appropriate permission
        return $user->hasPermissionTo('view_operation_type');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_operation_type');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OperationType $operationType): bool
    {
        // Only users with permission to update or the owner of the operation type can update it
        return $user->hasPermissionTo('update_operation_type');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OperationType $operationType): bool
    {
        // Only users with permission to delete or the owner of the operation type can delete it
        return $user->hasPermissionTo('delete_operation_type');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OperationType $operationType): bool
    {
        return $user->hasPermissionTo('restore_operation_type');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OperationType $operationType): bool
    {
        return $user->hasPermissionTo('force_delete_operation_type');
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
        return $user->hasPermissionTo('delete_operation_types');
        
    }
}
