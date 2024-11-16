<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RoleCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('category')->get();
        $roleCategories = RoleCategory::all();

        return Inertia::render('Roles/Index', [
            'roles' => $roles,
            'roleCategories' => $roleCategories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role_category_id' => 'required|exists:role_categories,id',
        ]);

        $role = Role::create($validated);

        return response()->json($role, 201);
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role_category_id' => 'required|exists:role_categories,id',
        ]);

        $role->update($validated);

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
