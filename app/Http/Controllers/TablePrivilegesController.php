<?php

namespace App\Http\Controllers;


use App\Models\Privilege;
use App\Models\TableToPermission;
use App\Models\Permission;
use App\Models\Table;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate; // Preserved for future use


class TablePrivilegesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tablePrivileges = Privilege::with('tableToPermission')->get();
        $tablePermissions = TableToPermission::with(['table','permission'])->get();

        $tables = Table::with('tableToPermissions.permission')
    ->get()
    ->map(function ($table) {
        $table->name = ucfirst($table->name);
        return $table;
    });
        $permissions = Permission::all();
        return Inertia::render('User/Privileges', [
            'tablePrivileges' => $tablePrivileges,
            'tablePermissions' => $tablePermissions,
            'permissions' => $permissions,
            'tables' => $tables,
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
    Gate::authorize('create', new TableToPermission());

    $validated = $request->validate([
        'permissions' => 'required|array',
        'permissions.*' => 'array',
        'permissions.*.*' => 'integer|exists:permissions,id',
    ]);

    foreach ($validated['permissions'] as $tableId => $permissionIds) {

        $existingPermissions = TableToPermission::where('table_id', $tableId)
            ->pluck('permission_id')
            ->toArray();


        $permissionsToDelete = array_diff($existingPermissions, $permissionIds);


        TableToPermission::where('table_id', $tableId)
            ->whereIn('permission_id', $permissionsToDelete)
            ->get()
            ->each(function ($tableToPermission) {

                Privilege::where('table_to_permission_id', $tableToPermission->id)->delete();
                $tableToPermission->delete();
            });

        foreach ($permissionIds as $permissionId) {
            $tableToPermission = TableToPermission::updateOrCreate(
                ['table_id' => $tableId, 'permission_id' => $permissionId],
                ['table_id' => $tableId, 'permission_id' => $permissionId]
            );


            $tableName = Table::find($tableId)->name;
            $permissionName = Permission::find($permissionId)->name;


            $privilegeName = "{$permissionName} {$tableName}";


            Privilege::updateOrCreate(
                ['table_to_permission_id' => $tableToPermission->id],
                ['privilege' => $privilegeName]
            );
        }
    }
    $tables = Table::with('tableToPermissions.permission')->get();

    return response()->json( $tables, 200);


}




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
