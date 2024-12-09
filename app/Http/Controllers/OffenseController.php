<?php

namespace App\Http\Controllers;

use App\Models\Offense;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use App\Models\User; // Preserved for potential future use

class OffenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch only non-deleted Offenses
        $Offenses = Offense::whereNull('deleted_at')->get();

        return Inertia::render('Offense/Index', [
            'offenses' => $Offenses,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        Gate::authorize('create', new Offense);


        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'penalty' => 'required|string|max:255',
        ]);



        $Offense = Offense::create($validated);

        return response()->json($Offense, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offense $Offense)
    {

        Gate::authorize('update', $Offense);


        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'penalty' => 'required|string|max:255',
        ]);

        $Offense->update($validated);

        return response()->json($Offense, 200);
    }

    public function show(Offense $offense)
    {
        return Inertia::render('Offense/Show', [
            'offense' => $offense
        ]);
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy(Offense $Offense)
    {

        Gate::authorize('delete', $Offense);

        $Offense->delete(); // Soft delete

        return response()->json(['message' => 'Offense soft-deleted successfully.'], 200);
    }

    /**
     * Bulk soft delete selected Offenses.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);

        Offense::whereIn('id', $validated['ids'])->delete(); // Soft delete

        return response()->json(['message' => 'Selected Offenses soft-deleted successfully.'], 200);
    }

    /**
     * Restore a soft-deleted Offense.
     */
    public function restore($id)
    {
        $Offense = Offense::withTrashed()->findOrFail($id);
        $Offense->restore();

        return response()->json(['message' => 'Offense restored successfully.'], 200);
    }

    /**
     * Bulk restore selected Offenses.
     */
    public function bulkRestore(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);

        Offense::withTrashed()->whereIn('id', $validated['ids'])->restore();

        return response()->json(['message' => 'Selected Offenses restored successfully.'], 200);
    }

    /**
     * Fetch all deleted Offenses for review.
     */
    public function trashed()
    {
        $trashedOffenses = Offense::onlyTrashed()->get();

        return response()->json($trashedOffenses, 200);
    }
}
