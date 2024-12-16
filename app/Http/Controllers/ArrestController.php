<?php

namespace App\Http\Controllers;

use App\Models\Arrest;
use App\Models\Confiscate;
use App\Models\Suspect;
use App\Models\Crime;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ArrestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch only non-deleted arrests
        $arrests = Arrest::with('suspect')->whereNull('deleted_at')->get();
        $suspects = Suspect::all();
        $confiscates = Confiscate::all();
        $crimes = Crime::all();


        return Inertia::render('Arrests/Index', [
            'arrests' => $arrests,
            'suspects' => $suspects,
            'confiscates' => $confiscates,
            'crimes' => $crimes,

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
/**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
    Gate::authorize('create', new Arrest);

    $validated = $request->validate([
        'description' => 'required|string|max:255',
        'date' => 'required|string',
        'proof' => 'nullable|string|max:255',
        'suspect_id' => 'required|exists:suspects,id',
    ]);

    DB::beginTransaction();

    try {

        $arrest = Arrest::create($validated);


        $arrest->suspect->update([
            'status' => Suspect::STATUS_ARRESTED,
        ]);


        $arrest->load('suspect');

        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();


        return response()->json([
            'message' => 'Failed to create arrest.',
            'error' => $e->getMessage(),
        ], 500);
    }

    return response()->json($arrest, 201);
}



    /**
     * Display the specified resource.
     */
    public function show(Arrest $arrest)
    {
        return Inertia::render('Arrests/Show', [
            'arrest' => $arrest->load('confiscate'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Arrest $arrest)
    {
        $confiscates = Confiscate::all();
        return Inertia::render('Arrests/Edit', [
            'arrest' => $arrest,
            'confiscates' => $confiscates,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $arrest = Arrest::findOrFail($id);
    Gate::authorize('update', $arrest);

    $validated = $request->validate([
        'description' => 'required|string|max:255',
        'date' => 'required|string',
        'proof' => 'nullable|string|max:255',
        'suspect_id' => 'required|exists:suspects,id',
    ]);

    DB::beginTransaction();

    try {
        // Update arrest record
        $arrest->update($validated);

        // Load relationships for response
        $arrest->load('suspect');

        DB::commit(); // Commit transaction
    } catch (\Exception $e) {
        DB::rollBack(); // Rollback on failure

        return response()->json([
            'message' => 'Failed to update arrest.',
            'error' => $e->getMessage(),
        ], 500);
    }

    return response()->json($arrest, 201);
}


    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy($id)
{
    $arrest = Arrest::findOrFail($id);
    Gate::authorize('delete', $arrest);

    DB::beginTransaction();

    try {
        $arrest->delete(); // Soft delete

        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'message' => 'Failed to delete arrest.',
            'error' => $e->getMessage(),
        ], 500);
    }

    return response()->json('Arrest deleted successfully.');
}

    /**
     * Bulk soft delete selected arrests.
     */
    public function batchDelete(Request $request)
{
    $validated = $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'exists:arrests,id',
    ]);

    DB::beginTransaction();

    try {
        Arrest::whereIn('id', $validated['ids'])->delete(); // Soft delete

        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'message' => 'Failed to delete selected arrests.',
            'error' => $e->getMessage(),
        ], 500);
    }

    return response()->json([
        'message' => 'Selected arrests deleted successfully.',
    ]);
}


    /**
     * Restore a soft-deleted arrest.
     */
    public function restore($id)
    {
        $arrest = Arrest::withTrashed()->findOrFail($id);

        DB::beginTransaction();

        try {
            $arrest->restore();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to restore arrest.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json('Arrest restored successfully.');
    }


    /**
     * Bulk restore selected arrests.
     */
    public function bulkRestore(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:arrests,id',
        ]);

        DB::beginTransaction();

        try {
            Arrest::withTrashed()->whereIn('id', $validated['ids'])->restore();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to restore selected arrests.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Selected arrests restored successfully.',
        ]);
    }

}
