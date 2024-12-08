<?php

namespace App\Http\Controllers;

use App\Models\SuspectToOffense;
use App\Models\Suspect;
use App\Models\Offense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SuspectToOffenseController extends Controller
{
    /**
     * Display a listing of the associations.
     */
    public function index()
    {
        $associations = SuspectToOffense::with(['suspect', 'offense'])->get();

        return response()->json($associations, 200);
    }

    /**
     * Store a newly created association in the database.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', new SuspectToOffense());

        $validated = $request->validate([
            'suspect_id' => 'required|exists:suspects,id',
            'offense_id' => 'required|exists:offenses,id',
        ]);

        DB::beginTransaction();
        try {
            $association = SuspectToOffense::create($validated);

            DB::commit();
            $association->load(['suspect', 'offense']);
            return response()->json($association, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create association.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified association in the database.
     */
    public function update(Request $request, SuspectToOffense $suspectToOffense)
    {
        Gate::authorize('update', $suspectToOffense);

        $validated = $request->validate([
            'suspect_id' => 'required|exists:suspects,id',
            'offense_id' => 'required|exists:offenses,id',
        ]);

        DB::beginTransaction();
        try {
            $suspectToOffense->update($validated);

            DB::commit();
            $suspectToOffense->load(['suspect', 'offense']);
            return response()->json($suspectToOffense, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update association.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Soft delete the specified association.
     */
    public function destroy(SuspectToOffense $suspectToOffense)
    {
        Gate::authorize('delete', $suspectToOffense);

        $suspectToOffense->delete();

        return response()->json(['message' => 'Association soft-deleted'], 200);
    }

    /**
     * Batch soft delete associations.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        SuspectToOffense::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Associations soft-deleted'], 200);
    }

    /**
     * Restore a soft-deleted association.
     */
    public function restore($id)
    {
        $suspectToOffense = SuspectToOffense::withTrashed()->findOrFail($id);
        $suspectToOffense->restore();

        return response()->json(['message' => 'Association restored successfully.'], 200);
    }

    /**
     * Bulk restore soft-deleted associations.
     */
    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        SuspectToOffense::withTrashed()->whereIn('id', $request->ids)->restore();

        return response()->json(['message' => 'Selected associations restored successfully.'], 200);
    }

    /**
     * Display all soft-deleted associations.
     */
    public function trashed()
    {
        $trashed = SuspectToOffense::onlyTrashed()->with(['suspect', 'offense'])->get();

        return response()->json($trashed, 200);
    }
}
