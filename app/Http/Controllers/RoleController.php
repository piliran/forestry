<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\RoleCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with(['category','permissions'])->get();
        $permissions = Permission::all();
        $roleCategories = RoleCategory::all();

        return Inertia::render('Roles/Index', [
            'roles' => $roles,
            'roleCategories' => $roleCategories,
            'permissions' => $permissions,
        ]);
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'role_category_id' => 'required|exists:role_categories,id',
    //     ]);

    //     $role = Role::create($validated);

    
    //     $role->load('category');

    //     return response()->json($role, 201);
    // }

    public function store(Request $request)
    {
       

        $request->validate([
            'name' => 'required|string|max:255',
            'role_category_id' => 'required|exists:role_categories,id',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',  
        ]);

        DB::beginTransaction();
        try {
            
            $role = Role::create([
                'name' => $request->input('name'),
                'role_category_id' => $request->input('role_category_id'),
            ]);

           

            $permissions = $request->input('permissions');
          
            $role->permissions()->sync($permissions);
       
            

            DB::commit();
        
            $role->load(['category','permissions']);
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
        $validated= $request->validate([
            'name' => 'required|string|max:255',
            'role_category_id' => 'required|exists:role_categories,id',
            // 'permissions' => 'required|array',
            // 'permissions.*' => 'exists:permissions,id',  // Ensures each permission exists in the permissions table
        ]);

        $role->update($validated);

         // Sync permissions with the role
         $role->permissions()->sync($request->permissions); // This will add new permissions and remove unticked ones


       
        $role->load(['category','permissions']);

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
