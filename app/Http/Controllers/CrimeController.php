<?php

namespace App\Http\Controllers;

use App\Models\Crime;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate; // Preserved for potential future use
use App\Models\User; // Preserved for potential future use

class CrimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch only non-deleted crimes
        $crimes = Crime::whereNull('deleted_at')->get();

        return Inertia::render('Department/Crimes', [
            'crimes' => $crimes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'penalty' => 'required|string|max:255',
        ]);

        $crime = Crime::create($validated);

        return response()->json($crime, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Crime $crime)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'penalty' => 'required|string|max:255',
        ]);

        $crime->update($validated);

        return response()->json($crime, 200);
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy(Crime $crime)
    {
        $crime->delete(); // Soft delete

        return response()->json(['message' => 'Crime soft-deleted successfully.'], 200);
    }

    /**
     * Bulk soft delete selected crimes.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);

        Crime::whereIn('id', $validated['ids'])->delete(); // Soft delete

        return response()->json(['message' => 'Selected crimes soft-deleted successfully.'], 200);
    }

    /**
     * Restore a soft-deleted crime.
     */
    public function restore($id)
    {
        $crime = Crime::withTrashed()->findOrFail($id);
        $crime->restore();

        return response()->json(['message' => 'Crime restored successfully.'], 200);
    }

    /**
     * Bulk restore selected crimes.
     */
    public function bulkRestore(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);

        Crime::withTrashed()->whereIn('id', $validated['ids'])->restore();

        return response()->json(['message' => 'Selected crimes restored successfully.'], 200);
    }

    /**
     * Fetch all deleted crimes for review.
     */
    public function trashed()
    {
        $trashedCrimes = Crime::onlyTrashed()->get();

        return response()->json($trashedCrimes, 200);
    }
}
