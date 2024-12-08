<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Zone;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch non-deleted districts
        $districts = District::whereNull('deleted_at')->with('zone')->get();
        $zones = Zone::all();

        return Inertia::render('District/Index', [
            'districts' => $districts,
            'zones' => $zones,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', new District());

        $validated = $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'name' => 'required|string|max:255',
        ]);

        $district = District::create($validated);
        $district->load('zone');

        return response()->json($district, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, District $district)
    {
        Gate::authorize('update', $district);

        $validated = $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'name' => 'required|string|max:255',
        ]);

        $district->update($validated);
        $district->load('zone');

        return response()->json($district, 200);
    }

    /**
     * Soft delete the specified resource.
     */
    public function destroy(District $district)
    {
        Gate::authorize('delete', $district);

        $district->delete(); // Soft delete

        return response()->json(['message' => 'District soft-deleted successfully.'], 200);
    }

    /**
     * Bulk soft delete selected districts.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);

        District::whereIn('id', $validated['ids'])->delete(); // Soft delete

        return response()->json(['message' => 'Selected districts soft-deleted successfully.'], 200);
    }

    /**
     * Restore a soft-deleted district.
     */
    public function restore($id)
    {
        $district = District::withTrashed()->findOrFail($id);
        $district->restore();

        return response()->json(['message' => 'District restored successfully.'], 200);
    }

    /**
     * Bulk restore selected districts.
     */
    public function bulkRestore(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);

        District::withTrashed()->whereIn('id', $validated['ids'])->restore();

        return response()->json(['message' => 'Selected districts restored successfully.'], 200);
    }

    /**
     * Fetch all trashed districts for review.
     */
    public function trashed()
    {
        $trashedDistricts = District::onlyTrashed()->get();

        return response()->json($trashedDistricts, 200);
    }
}
