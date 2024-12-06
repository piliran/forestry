<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use App\Models\Permission;
use App\Models\Privilege;
use App\Models\Role;
use App\Models\District;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\RoleToPermission;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $users = User::with(['roles', 'district', 'permissions'])->whereNull('deleted_at')->get();
    //     $userRoles = UserRole::with(['user', 'role'])->get();
    //     $permissions = Permission::all();
    //     $roles = Role::all();
    //     $districts = District::all();

    //     return Inertia::render('User/UserList', [
    //         'users' => $users,
    //         'roles' => $roles,
    //         'permissions' => $permissions,
    //         'userRoles' => $userRoles,
    //         'districts' => $districts,
    //         'isAdmin' => auth()->user()->isAdministrator(),
    //         'can' => [
    //             'createUser' => auth()->user()->hasPermissionTo('create_user'),
    //             'editUser' => auth()->user()->hasPermissionTo('edit_user'),
    //             'deleteUser' => auth()->user()->hasPermissionTo('delete_user'),
    //         ],
    //     ]);
    // }

    public function index()
    {

        // $users = User::with(['roles', 'district', 'permissions'])
        //     ->whereNull('deleted_at')
        //     ->get();
            $users = User::with(['roles', 'district', 'privileges'])
            ->whereNull('deleted_at')
            ->get();


        $roles = Role::with('privileges')->get();
        // $roles = Role::with('permissions')->get();


        $permissions = Permission::with('roles')->get();
        $privileges = Privilege::with('roles')->get();


        $districts = District::all();


        $userRoles = UserRole::with(['user', 'role'])->get();

        return Inertia::render('User/UserList', [
            'users' => $users,
            'roles' => $roles,
            'permissions' => $permissions,
            'privileges' => $privileges,
            'userRoles' => $userRoles,
            'districts' => $districts,
            'isAdmin' => auth()->user()->isAdministrator(),
            'can' => [
                'createUser' => auth()->user()->hasPermissionTo('create_user'),
                'editUser' => auth()->user()->hasPermissionTo('edit_user'),
                'deleteUser' => auth()->user()->hasPermissionTo('delete_user'),
            ],
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'gender' => 'nullable|string|max:255',
            'DOB' => 'nullable|date',
            'district_id' => 'nullable|integer',

            'national_id' => 'nullable|string|max:255',
        ]);

        if ($request->user()->cannot('create_user', User::class)) {
            abort(403);
        }

        $user = User::create([
            'title' => $request->title,
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'DOB' => $request->DOB,
            'district_id' => $request->district_id,
            'national_id' => $request->national_id,
            'password' => bcrypt('12345678'),
        ]);

        $user->load(['roles', 'district', 'permissions']);

        return response()->json($user, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'gender' => 'nullable|string|max:255',
            'DOB' => 'nullable|date',
            'district_id' => 'nullable|integer',
            'national_id' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
        ]);

        if ($request->user()->cannot('edit_user', User::class)) {
            abort(403);
        }

        $user->update([
            'title' => $request->title,
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'DOB' => $request->DOB,
            'district_id' => $request->district_id,
            'national_id' => $request->national_id,
            'phone' => $request->phone,
        ]);

        $user->load('district');

        return response()->json($user);
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (auth()->user()->cannot('delete_user', User::class)) {
            abort(403, 'Unauthorized action.');
        }

        $user->delete(); // Soft delete
        return response()->json('User deleted successfully.');
    }

    /**
     * Bulk soft delete selected users.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);

        User::whereIn('id', $request->ids)->delete(); // Soft delete
        return response()->json([
            'message' => 'Selected users deleted successfully.',
        ]);
    }

    /**
     * Restore a soft-deleted user.
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return response()->json('User restored successfully.');
    }
}
