<?php

namespace App\Http\Controllers;

use App\Models\Species;
use App\Models\SpeciesCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class SpeciesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch non-deleted species
        $species = Species::with('category')->whereNull('deleted_at')->get();
        $speciesCategory = SpeciesCategory::all();

        return Inertia::render('Species/List', [
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
        Gate::authorize('create', new Species());

        $request->validate([
            'name' => 'required|string|max:255',
            'unplanted_seedlings_count' => 'required|numeric',
            'planted_seedlings_count' => 'required|numeric',
            'unmatured_specie_count' => 'required|numeric',
            'matured_specie_count' => 'required|numeric',
            'description' => 'required|string',
            'specie_cat_id' => 'required|exists:specie_categories,id',
        ]);

        $species = Species::create($request->all());
        $species->load('category');

        return response()->json($species, 201);  // Return the created species
    }

    /**
     * Display the specified resource.
     */
    public function show(Species $species)
    {
        //
        $species->load('category');
        return Inertia::render('Species/Show',[
            'species' => $species
        ]);
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
        Gate::authorize('update', $species);

        $request->validate([
            'name' => 'required|string|max:255',
            'unplanted_seedlings_count' => 'required|numeric',
            'planted_seedlings_count' => 'required|numeric',
            'unmatured_specie_count' => 'required|numeric',
            'matured_specie_count' => 'required|numeric',
            'description' => 'required|string',
            'specie_cat_id' => 'required|exists:specie_categories,id',
        ]);

        $species = Species::find($request->id);
        $species->update($request->all());
        $species->load('category');


        return response()->json($species);
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy(Species $species)
    {
        Gate::authorize('delete', $species);

        $species->delete();  // Soft delete

        return response()->json(['message' => 'Species soft-deleted successfully.'], 200);
    }

    /**
     * Bulk soft delete selected species.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        Species::whereIn('id', $validated['ids'])->delete();  // Soft delete

        return response()->json(['message' => 'Selected species soft-deleted successfully.'], 200);
    }

    /**
     * Restore a soft-deleted species.
     */
    public function restore($id)
    {
        $species = Species::withTrashed()->findOrFail($id);
        $species->restore();  // Restore the soft-deleted species

        return response()->json(['message' => 'Species restored successfully.'], 200);
    }

    /**
     * Bulk restore soft-deleted species.
     */
    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Species::withTrashed()->whereIn('id', $request->ids)->restore();  // Restore soft-deleted species

        return response()->json(['message' => 'Selected species restored successfully.'], 200);
    }

    /**
     * Fetch trashed species.
     */
    public function trashed()
    {
        $trashedSpecies = Species::onlyTrashed()->get();  // Fetch all soft-deleted species

        return response()->json($trashedSpecies, 200);
    }
}
