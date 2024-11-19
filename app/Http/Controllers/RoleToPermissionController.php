<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\RoleToPermission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleToPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roleToPermissions = RoleToPermission::with(['role', 'permission'])->get();
        return Inertia::render('RoleToPermission/Index', [
            'roleToPermissions' => $roleToPermissions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
     * Show the form for editing the specified resource.
     */
    public function edit(RoleToPermission $roleToPermission)
    {
        //
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
     * Remove the specified resource from storage.
     */
    public function destroy(RoleToPermission $roleToPermission)
    {
        $roleToPermission->delete();

        return response()->json('Role-to-Permission relationship deleted successfully.');
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

        RoleToPermission::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => 'Selected role-to-permission relationships deleted successfully.',
        ]);
    }
}
