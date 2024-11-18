<?php

namespace App\Http\Controllers;

use App\Models\SpecieCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SpecieCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specieCategories = SpecieCategory::all();
        return Inertia::render('SpecieCategories/Index', [
            'specieCategories' => $specieCategories,
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
            'description' => 'nullable|string',
        ]);

        $specieCategory = SpecieCategory::create($request->all());

        return response()->json($specieCategory, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SpecieCategory $specieCategory)
    {
        return Inertia::render('SpecieCategories/Show', [
            'specieCategory' => $specieCategory,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SpecieCategory $specieCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SpecieCategory $specieCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $specieCategory->update($request->all());

        return response()->json($specieCategory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpecieCategory $specieCategory)
    {
        $specieCategory->delete();

        return response()->json('Specie Category deleted successfully.');
    }

    /**
     * Bulk delete selected categories.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:specie_categories,id',
        ]);

        SpecieCategory::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => 'Selected categories deleted successfully.',
        ]);
    }
}
