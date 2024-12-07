<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\Privilege;
use App\Models\RoleCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class RoleController extends Controller
{
    // public function index()
    // {
    //     // Fetch non-deleted roles
    //     $roles = Role::whereNull('deleted_at')->with(['category','permissions'])->get();
    //     $permissions = Permission::all();
    //     $privileges = Privilege::with('tableToPermission')->get();
    //     $roleCategories = RoleCategory::all();

    //     return Inertia::render('Roles/Index', [
    //         'roles' => $roles,
    //         'roleCategories' => $roleCategories,
    //         'permissions' => $permissions,
    //         'privileges' => $privileges,
    //     ]);
    // }

    public function index()
    {
        // Fetch non-deleted roles
        $roles = Role::whereNull('deleted_at')->with(['category','privileges'])->get();
        $permissions = Permission::all();
        $privileges = Privilege::with('tableToPermission')->get();
        $roleCategories = RoleCategory::all();

        return Inertia::render('Roles/Index', [
            'roles' => $roles,
            'roleCategories' => $roleCategories,
            'permissions' => $permissions,
            'privileges' => $privileges,
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', new Role());

        $request->validate([
            'name' => 'required|string|max:255',
            'role_category_id' => 'required|exists:role_categories,id',
            'privileges' => 'required|array',
            'privileges.*' => 'exists:privileges,id',
        ]);


        // $role = Role::create([
        //     'name' => $request->input('name'),
        //     'role_category_id' => $request->input('role_category_id'),
        // ]);

        // $privileges = $request->input('privileges');
        // $role->privileges()->sync($privileges);



        // $role->load(['category','privileges']);
        // return response()->json($role, 200);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->input('name'),
                'role_category_id' => $request->input('role_category_id'),
            ]);

            $privileges = $request->input('privileges');
            $role->privileges()->sync($privileges);

            DB::commit();

            $role->load(['category','privileges']);
            return response()->json($role, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create role.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function update(Request $request, Role $role)
    {
        Gate::authorize('update', $role);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role_category_id' => 'required|exists:role_categories,id',
        ]);

        $role->update($validated);

        // Sync permissions with the role
        $role->privileges()->sync($request->privileges);

        $role->load(['category', 'privileges']);

        return response()->json($role, 200);
    }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'role_category_id' => 'required|exists:role_categories,id',
    //         'permissions' => 'required|array',
    //         'permissions.*' => 'exists:permissions,id',
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         $role = Role::create([
    //             'name' => $request->input('name'),
    //             'role_category_id' => $request->input('role_category_id'),
    //         ]);

    //         $permissions = $request->input('permissions');
    //         $role->permissions()->sync($permissions);

    //         DB::commit();

    //         $role->load(['category','permissions']);
    //         return response()->json($role, 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'message' => 'Failed to create role.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    // public function update(Request $request, Role $role)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'role_category_id' => 'required|exists:role_categories,id',
    //     ]);

    //     $role->update($validated);

    //     // Sync permissions with the role
    //     $role->permissions()->sync($request->permissions);

    //     $role->load(['category', 'permissions']);

    //     return response()->json($role, 200);
    // }

    public function destroy(Role $role)
    {
        Gate::authorize('delete', $role);

        $role->delete();  // Soft delete

        return response()->json(['message' => 'Role soft-deleted'], 200);
    }

    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        Role::whereIn('id', $validated['ids'])->delete();  // Soft delete

        return response()->json(['message' => 'Roles soft-deleted'], 200);
    }

    // Soft delete a role
    public function restore($id)
    {
        $role = Role::withTrashed()->findOrFail($id);
        $role->restore();  // Restore the soft-deleted role

        return response()->json(['message' => 'Role restored successfully.'], 200);
    }

    // Restore multiple roles at once
    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Role::withTrashed()->whereIn('id', $request->ids)->restore();  // Restore the soft-deleted roles

        return response()->json(['message' => 'Selected roles restored successfully.'], 200);
    }

    // Fetch trashed roles
    public function trashed()
    {
        $trashedRoles = Role::onlyTrashed()->get();  // Fetch all soft-deleted roles

        return response()->json($trashedRoles, 200);
    }
}
