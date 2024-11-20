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
        $speciesCategory = SpeciesCategory::all();

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

        return response()->json($speciesCategory, 201);
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


    $category =SpeciesCategory::find($request->id);
    $category->update($request->all());

    

    return response()->json($category);  // Return the updated category
}

    /**
     * Remove the specified resource from storage.
     */
  
    public function destroy(SpeciesCategory $speciesCategory)
    {
        $speciesCategory->delete();

        return response()->json(['message' => 'Category deleted'], 200);
    }

    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        SpeciesCategory::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Category deleted'], 200);
    }
}
