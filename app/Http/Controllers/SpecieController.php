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
        $species = Specie::with('category')->get();
        return Inertia::render('Species/Index', [
            'species' => $species,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = SpecieCategory::all();
        return Inertia::render('Species/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'specie_cat_id' => 'required|exists:specie_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'matured_specie_count' => 'required|numeric|min:0',
            'unmatured_specie_count' => 'required|numeric|min:0',
            'planted_seedlings_count' => 'required|numeric|min:0',
            'unplanted_seedlings_count' => 'required|numeric|min:0',
        ]);

        $specie = Specie::create($request->all());

        return response()->json($specie, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Specie $specie)
    {
        $specie->load('category');
        return Inertia::render('Species/Show', [
            'specie' => $specie,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Specie $specie)
    {
        $categories = SpecieCategory::all();
        return Inertia::render('Species/Edit', [
            'specie' => $specie,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Specie $specie)
    {
        $request->validate([
            'specie_cat_id' => 'required|exists:specie_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'matured_specie_count' => 'required|numeric|min:0',
            'unmatured_specie_count' => 'required|numeric|min:0',
            'planted_seedlings_count' => 'required|numeric|min:0',
            'unplanted_seedlings_count' => 'required|numeric|min:0',
        ]);

        $specie->update($request->all());

        return response()->json($specie);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specie $specie)
    {
        $specie->delete();

        return response()->json('Specie deleted successfully.');
    }

    /**
     * Bulk delete selected species.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:species,id',
        ]);

        Specie::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => 'Selected species deleted successfully.',
        ]);
    }
}
