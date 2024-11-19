<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the user roles.
     */
    public function index()
    {
        // Fetch all user roles, including related users and roles
        $userRoles = UserRole::with(['user', 'role'])->get();
        $roles = Role::all();
        $users = User::all();

        // Return the data to the Inertia frontend
        return Inertia::render('UserRoles/Index', [
            'userRoles' => $userRoles,
            'roles' => $roles,
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created user role in storage.
     */
    public function store(Request $request)
    {
      
        DB::beginTransaction();

        try {
            // Find the user by the user ID
            $user = User::findOrFail($request->input('userId'));

            // Retrieve the array of role IDs from the request
            $roleIds = $request->input('roleIds');
      
            // Sync roles with the user (this will add new roles and remove any removed roles)
            $user->roles()->sync($roleIds);  // Assuming User has a many-to-many relationship with Role

            DB::commit();

          
            $user->load('roles');

            return response()->json($user, 200);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error
            Log::error('Error assigning roles to user: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'message' => 'Failed to assign roles.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

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
        // Delete the user role
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

        // Delete the roles in a batch
        UserRole::whereIn('id', $validated['ids'])->delete();

        // Return a success message
        return response()->json(['message' => 'User roles deleted'], 200);
    }
}
