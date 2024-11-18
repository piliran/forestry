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
        //
        $specieCategories = SpecieCategory::all();
        return Inertia::render('SpecieCategories/Index',[
            'specieCategories' => $specieCategories
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
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255'
        ]);

        $category = SpecieCategory::create($request->all());

        return response()->json($category, 201); // Return the created category

    }

    /**
     * Display the specified resource.
     */
    public function show(SpecieCategory $specieCategory)
    {
        //
        return Inertia::render('SpecieCategories/Show',[
            'specieCategory'=>$specieCategory
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
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255'
        ]);

        $specieCategory->update($request->all()); // Return the updated category

        return response()->json($specieCategory, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpecieCategory $specieCategory)
    {
        //
        $specieCategory->delete();

        return response()->json('Specie Category Deleted Successfully.');
    }

    public function bulkDelete(Request $request){
        // Validate the incoming request to ensure 'ids' is an array of integers
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:specie_categories,id', // Ensure each id exists in the database
        ]);

        // Delete the categories by their IDs
        SpecieCategory::whereIn('id', $request->ids)->delete();

        // Return a response indicating successful deletion
        return response()->json([
            'message' => 'Selected Specie Categories Deleted Successfully.'
        ]);
    }
}
