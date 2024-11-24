<?php

namespace App\Http\Controllers;

use App\Models\SpeciesCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class SpeciesCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch non-deleted species categories
        $speciesCategory = SpeciesCategory::whereNull('deleted_at')->get();

        return Inertia::render('Department/SpeciesTypes', [
            'speciesCategory' => $speciesCategory,
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
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $speciesCategory = SpeciesCategory::create($request->all());

        return response()->json($speciesCategory, 201);  // Return the created category
    }

    /**
     * Display the specified resource.
     */
    public function show(SpeciesCategory $speciesCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SpeciesCategory $speciesCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SpeciesCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        // Find and update the category
        $category = SpeciesCategory::find($request->id);
        $category->update($request->all());

        return response()->json($category);  // Return the updated category
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy(SpeciesCategory $speciesCategory)
    {
        $speciesCategory->delete();  // Soft delete

        return response()->json(['message' => 'Category soft-deleted successfully.'], 200);
    }

    /**
     * Bulk soft delete selected categories.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        SpeciesCategory::whereIn('id', $validated['ids'])->delete();  // Soft delete

        return response()->json(['message' => 'Selected categories soft-deleted successfully.'], 200);
    }

    /**
     * Restore a soft-deleted species category.
     */
    public function restore($id)
    {
        $category = SpeciesCategory::withTrashed()->findOrFail($id);
        $category->restore();  // Restore the soft-deleted category

        return response()->json(['message' => 'Category restored successfully.'], 200);
    }

    /**
     * Bulk restore soft-deleted species categories.
     */
    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        SpeciesCategory::withTrashed()->whereIn('id', $request->ids)->restore();  // Restore soft-deleted categories

        return response()->json(['message' => 'Selected categories restored successfully.'], 200);
    }

    /**
     * Fetch trashed species categories.
     */
    public function trashed()
    {
        $trashedCategories = SpeciesCategory::onlyTrashed()->get();  // Fetch all soft-deleted categories

        return response()->json($trashedCategories, 200);
    }
}
