<?php

namespace App\Http\Controllers;

use App\Models\RoleCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class RoleCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch non-deleted categories
        $roleCategories = RoleCategory::whereNull('deleted_at')->get();
        
        return Inertia::render('RoleCategories/Index', [
            'roleCategories' => $roleCategories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);
    
        $category = RoleCategory::create($request->all());
    
        return response()->json($category, 201);  // Return the created category
    }

    /**
     * Display the specified resource.
     */
    public function show(RoleCategory $roleCategory)
    {
        return Inertia::render('RoleCategories/Show', [
            'roleCategory' => $roleCategory
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoleCategory $roleCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $roleCategory->update($request->all());

        return response()->json($roleCategory);  // Return the updated category
    }

    /**
     * Soft delete the specified resource.
     */
    public function destroy(RoleCategory $roleCategory)
    {
        $roleCategory->delete();  // Soft delete

        return response()->json('Role Category soft-deleted successfully.');
    }

    /**
     * Bulk soft delete selected categories.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:role_categories,id',
        ]);

        RoleCategory::whereIn('id', $request->ids)->delete();  // Soft delete

        return response()->json([
            'message' => 'Selected categories soft-deleted successfully.'
        ]);
    }

    /**
     * Restore a soft-deleted category.
     */
    public function restore($id)
    {
        $roleCategory = RoleCategory::withTrashed()->findOrFail($id);
        $roleCategory->restore();

        return response()->json(['message' => 'Role Category restored successfully.'], 200);
    }

    /**
     * Bulk restore selected soft-deleted categories.
     */
    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        RoleCategory::withTrashed()->whereIn('id', $request->ids)->restore();

        return response()->json(['message' => 'Selected categories restored successfully.'], 200);
    }

    /**
     * Fetch all soft-deleted categories (trashed).
     */
    public function trashed()
    {
        $trashedCategories = RoleCategory::onlyTrashed()->get();

        return response()->json($trashedCategories, 200);
    }
}
