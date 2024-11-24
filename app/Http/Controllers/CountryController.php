<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate; // Preserved for future use
use App\Models\User; // Preserved for future use

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch only non-deleted countries
        $countries = Country::whereNull('deleted_at')->get();

        return Inertia::render('Country/Index', [
            'countries' => $countries,
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $country = Country::create($validated);

        return response()->json($country, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $country->update($validated);

        return response()->json($country, 200);
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        $country->delete(); // Soft delete

        return response()->json(['message' => 'Country soft-deleted successfully.'], 200);
    }

    /**
     * Bulk soft delete selected countries.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);

        Country::whereIn('id', $validated['ids'])->delete(); // Soft delete

        return response()->json(['message' => 'Selected countries soft-deleted successfully.'], 200);
    }

    /**
     * Restore a soft-deleted country.
     */
    public function restore($id)
    {
        $country = Country::withTrashed()->findOrFail($id);
        $country->restore();

        return response()->json(['message' => 'Country restored successfully.'], 200);
    }

    /**
     * Bulk restore selected countries.
     */
    public function bulkRestore(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);

        Country::withTrashed()->whereIn('id', $validated['ids'])->restore();

        return response()->json(['message' => 'Selected countries restored successfully.'], 200);
    }
}
