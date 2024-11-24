<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\RoleToPermission;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class RoleToPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch non-deleted relationships
        $roleToPermissions = RoleToPermission::whereNull('deleted_at')->with(['role', 'permission'])->get();
        return Inertia::render('RoleToPermission/Index', [
            'roleToPermissions' => $roleToPermissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        $roleToPermission = RoleToPermission::create($request->all());

        return response()->json($roleToPermission, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RoleToPermission $roleToPermission)
    {
        return Inertia::render('RoleToPermission/Show', [
            'roleToPermission' => $roleToPermission->load(['role', 'permission']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoleToPermission $roleToPermission)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        $roleToPermission->update($request->all());

        return response()->json($roleToPermission);
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy(RoleToPermission $roleToPermission)
    {
        $roleToPermission->delete();  // Soft delete

        return response()->json('Role-to-Permission relationship soft-deleted successfully.');
    }

    /**
     * Bulk delete selected role-to-permission records.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:role_to_permission,id',
        ]);

        RoleToPermission::whereIn('id', $request->ids)->delete();  // Soft delete

        return response()->json([
            'message' => 'Selected role-to-permission relationships soft-deleted successfully.',
        ]);
    }

    /**
     * Restore a soft-deleted role-to-permission relationship.
     */
    public function restore($id)
    {
        $roleToPermission = RoleToPermission::withTrashed()->findOrFail($id);
        $roleToPermission->restore();  // Restore the soft-deleted relationship

        return response()->json(['message' => 'Role-to-Permission relationship restored successfully.'], 200);
    }

    /**
     * Restore multiple soft-deleted role-to-permission relationships.
     */
    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        RoleToPermission::withTrashed()->whereIn('id', $request->ids)->restore();  // Restore the soft-deleted relationships

        return response()->json(['message' => 'Selected role-to-permission relationships restored successfully.'], 200);
    }

    /**
     * Fetch trashed role-to-permission relationships.
     */
    public function trashed()
    {
        $trashedRoleToPermissions = RoleToPermission::onlyTrashed()->get();  // Fetch all soft-deleted relationships

        return response()->json($trashedRoleToPermissions, 200);
    }
}
