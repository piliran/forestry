<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch non-deleted permissions
        $permissions = Permission::whereNull('deleted_at')->get();

        return Inertia::render('User/Permissions', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', new Permission());

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'description' => 'nullable|string|max:255',
        ]);

        $permission = Permission::create($request->all());

        return response()->json($permission, 201); // Return the created permission
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        return Inertia::render('Permissions/Show', [
            'permission' => $permission,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        Gate::authorize('update', $permission);

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string|max:255',
        ]);

        $permission->update($request->all());

        return response()->json($permission); // Return the updated permission
    }

    /**
     * Soft delete the specified resource.
     */
    public function destroy(Permission $permission)
    {
        Gate::authorize('delete', $permission);

        $permission->delete(); // Soft delete

        return response()->json('Permission soft-deleted successfully.');
    }

    /**
     * Batch soft delete selected permissions.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:permissions,id',
        ]);

        Permission::whereIn('id', $validated['ids'])->delete(); // Soft delete

        return response()->json([
            'message' => 'Selected permissions soft-deleted successfully.',
        ]);
    }

    /**
     * Restore a soft-deleted permission.
     */
    public function restore($id)
    {
        $permission = Permission::withTrashed()->findOrFail($id);
        $permission->restore();

        return response()->json(['message' => 'Permission restored successfully.'], 200);
    }

    /**
     * Bulk restore selected soft-deleted permissions.
     */
    public function bulkRestore(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        Permission::withTrashed()->whereIn('id', $validated['ids'])->restore();

        return response()->json(['message' => 'Selected permissions restored successfully.'], 200);
    }

    /**
     * Fetch all soft-deleted permissions (trashed).
     */
    public function trashed()
    {
        $trashedPermissions = Permission::onlyTrashed()->get();

        return response()->json($trashedPermissions, 200);
    }
}
