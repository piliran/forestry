<?php

namespace App\Http\Controllers;

use App\Models\Species;
use App\Models\SpeciesCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SpeciesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $species = Species::with('speciesCategory')->get();
        $speciesCategory = SpeciesCategory::all();
    

        return Inertia::render('Department/SpeciesList', [
            'species' => $species,
            'speciesCategories' => $speciesCategory,
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
            'unplanted_seedlings_count' => 'required|numeric|max:255',
            'planted_seedlings_count' => 'required|numeric|max:255',
            'unmatured_specie_count' => 'required|numeric|max:255',
            'matured_specie_count' => 'required|numeric|max:255',
            'description' => 'required|string|max:255',
            'specie_cat_id' => 'required|exists:species_categories,id',
        ]);

        $species = Species::create($request->all());

        return response()->json($species, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Species $species)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Species $species)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Species $species)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unplanted_seedlings_count' => 'required|numeric|max:255',
            'planted_seedlings_count' => 'required|numeric|max:255',
            'unmatured_specie_count' => 'required|numeric|max:255',
            'matured_specie_count' => 'required|numeric|max:255',
            'description' => 'required|string|max:255',
            'specie_cat_id' => 'required|exists:species_categories,id',
        ]);

        $species->update($request->all());

        return response()->json($species);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Species $species)
    {
        $species->delete();

        return response()->json(['message' => 'species deleted'], 200);
    }

    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        Species::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'species deleted'], 200);
    }
}
