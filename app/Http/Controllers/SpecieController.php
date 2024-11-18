<?php

namespace App\Http\Controllers;

use App\Models\Specie;
use App\Models\SpecieCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SpecieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $species = Specie::with('category')->get();
        $specieCategories = SpecieCategory::all();

        return Inertia::render('Species/Index', [
            'species' => $species,
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
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specie_cat_id' => 'required|exists:specie_categories,id',
            'description' => 'required|string|max:255',
            'matured_specie_count' => 'required|integer|max:255',
            'unmatured_specie_count' => 'required|integer|max:255',
            'planted_seedlings_count' => 'required|integer|max:255',
            'unplanted_seedlings_count' => 'required|integer|max:255',

        ]);

        $specie = Specie::create($validated);

        return response()->json($specie, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Specie $specie)
    {
        //
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Specie $specie)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Specie $specie)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specie_cat_id' => 'required|exists:specie_categories,id',
            'description' => 'required|string|max:255',
            'matured_specie_count' => 'required|integer|max:255',
            'unmatured_specie_count' => 'required|integer|max:255',
            'planted_seedlings_count' => 'required|integer|max:255',
            'unplanted_seedlings_count' => 'required|integer|max:255',

        ]);

        $specie->update($validated);

        return response()->json($specie, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specie $specie)
    {
        //
        $specie->delete();

        return response()->json(['message' => 'Species Deleted Successfully'], 200);
    }

    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        Specie::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Selected Species Deleted Successfully'], 200);
    }

}
