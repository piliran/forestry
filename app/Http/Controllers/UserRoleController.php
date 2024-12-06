<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use App\Models\Role;
use App\Models\RoleToPermission;
use App\Models\RoleToPrivilege;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the user roles.
     */
    public function index()
    {
        // Fetch non-deleted user roles, including related users and roles
        $userRoles = UserRole::with(['user', 'role'])->whereNull('deleted_at')->get();
        $roles = Role::all();
        $users = User::all();

        // Return the data to the Inertia frontend
        return Inertia::render('UserRoles/Index', [
            'userRoles' => $userRoles,
            'roles' => $roles,
            'users' => $users,
        ]);
    }





    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Find the user by the user ID
            $user = User::findOrFail($request->input('userId'));

            // Retrieve the array of role IDs from the request
            $roleIds = $request->input('roleIds');

            // Sync roles with the user (this will add new roles and remove any removed roles)
            $user->roles()->sync($roleIds); // Assuming User has a many-to-many relationship with Role

            // Extract all permission IDs associated with the provided roles
            $privilegeIds = RoleToPrivilege::whereIn('role_id', $roleIds)
                ->pluck('privilege_id')
                ->unique(); // Get unique permission IDs

            // Insert the privileges into the user_permissions table
            $user->privileges()->sync($privilegeIds); // Assuming User has a many-to-many relationship with Permission

            DB::commit();

            $user->load('roles', 'privileges'); // Load roles and privileges for the response

            return response()->json($user, 200);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error
            Log::error('Error assigning roles and privileges to user: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'message' => 'Failed to assign roles and privileges.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


//     public function store(Request $request)
// {
//     DB::beginTransaction();

//     try {
//         // Find the user by the user ID
//         $user = User::findOrFail($request->input('userId'));

//         // Retrieve the array of role IDs from the request
//         $roleIds = $request->input('roleIds');

//         // Sync roles with the user (this will add new roles and remove any removed roles)
//         $user->roles()->sync($roleIds); // Assuming User has a many-to-many relationship with Role

//         // Extract all permission IDs associated with the provided roles
//         $permissionIds = RoleToPermission::whereIn('role_id', $roleIds)
//             ->pluck('permission_id')
//             ->unique(); // Get unique permission IDs

//         // Insert the permissions into the user_permissions table
//         $user->permissions()->sync($permissionIds); // Assuming User has a many-to-many relationship with Permission

//         DB::commit();

//         $user->load('roles', 'permissions'); // Load roles and permissions for the response

//         return response()->json($user, 200);
//     } catch (\Exception $e) {
//         DB::rollBack();

//         // Log the error
//         Log::error('Error assigning roles and permissions to user: ' . $e->getMessage());

//         // Return an error response
//         return response()->json([
//             'message' => 'Failed to assign roles and permissions.',
//             'error' => $e->getMessage(),
//         ], 500);
//     }
// }


    /**
     * Update the specified user role in storage.
     */
    public function update(Request $request, UserRole $userRole)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Update the user role
        $userRole->update($validated);

        // Load the related data
        $userRole->load(['user', 'role']);

        // Return the updated user role
        return response()->json($userRole, 200);
    }

    /**
     * Remove the specified user role from storage.
     */
    public function destroy(UserRole $userRole)
    {
        // Perform soft delete by setting 'deleted_at'
        $userRole->delete();

        // Return a success message
        return response()->json(['message' => 'User role deleted'], 200);
    }

    /**
     * Delete multiple user roles in a batch.
     */
    public function batchDelete(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate(['ids' => 'required|array']);

        // Perform soft delete for the roles in a batch
        UserRole::whereIn('id', $validated['ids'])->delete();

        // Return a success message
        return response()->json(['message' => 'User roles deleted'], 200);
    }
}
