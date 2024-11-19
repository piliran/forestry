<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\RoleCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('category')->get();
        $permissions = Permission::all();
        $roleCategories = RoleCategory::all();

        return Inertia::render('Roles/Index', [
            'roles' => $roles,
            'roleCategories' => $roleCategories,
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role_category_id' => 'required|exists:role_categories,id',
        ]);

        $role = Role::create($validated);

    
        $role->load('category');

        return response()->json($role, 201);
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255|unique:roles,name',
    //         'role_category_id' => 'required|exists:role_categories,id',
    //         'permissions' => 'required|array',
    //         'permissions.*' => 'exists:permissions,id', // Ensure each permission exists
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         // Create the new role
    //         $role = Role::create([
    //             'name' => $request->input('name'),
    //             'role_category_id' => $request->input('role_category_id'),
    //         ]);

    //         // Attach the permissions
    //         $permissions = $request->input('permissions');
    //         $role->permissions()->sync($permissions);

    //         DB::commit();

    //         return response()->json([
    //             'message' => 'Role created successfully.',
    //             'role' => $role->load('permissions'),
    //         ], 201);
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'message' => 'Failed to create role.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }


    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role_category_id' => 'required|exists:role_categories,id',
        ]);

        $role->update($validated);

        $role->load('category');

        return response()->json($role, 200);
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json(['message' => 'Role deleted'], 200);
    }

    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        Role::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Roles deleted'], 200);
    }
}
