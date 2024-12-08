<?php

namespace App\Http\Controllers;

use App\Models\Arrest;
use App\Models\Confiscate;
use App\Models\Suspect;
use App\Models\Crime;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate; // Preserved for future use
use App\Models\User; // Preserved for future use

class ArrestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch only non-deleted arrests
        $arrests = Arrest::with(['suspect', 'crime'])->whereNull('deleted_at')->get();
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
    public function store(Request $request)
    {
        Gate::authorize('create', new Arrest);


      $validated=  $request->validate([
            'description' => 'required|string|max:255',
            'date' => 'required|string',
            'location' => 'nullable|string|max:255',
            'proof' => 'nullable|string|max:255',
            'suspect_id' => 'required|exists:suspects,id',
            // 'confiscate_id' => 'required|exists:confiscates,id',
            'crime_id' => 'required|exists:crimes,id',
            // 'status' => 'required|string|in:' . implode(',', Suspect::getStatuses()),


        ]);


        $arrest = Arrest::create($validated);
        $arrest->load('suspect');
        // $arrest->load('confiscate');
        $arrest->load('crime');

        return response()->json($arrest, 201); // Return the created arrest
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

        $request->validate([
            'description' => 'required|string|max:255',
            'date' => 'required|string',
            'location' => 'nullable|string|max:255',
            'proof' => 'nullable|string|max:255',
            'suspect_id' => 'required|exists:suspects,id',
            // 'confiscate_id' => 'required|exists:confiscates,id',
            'crime_id' => 'required|exists:crimes,id',
        ]);

        $arrest->update($request->all());
        $arrest->load(['suspect','crimes']); // Return the updated arrest

        return response()->json($arrest, 201);
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy($id)
    {
        $arrest = Arrest::findOrFail($id);
        Gate::authorize('delete', $arrest);

        $arrest->delete(); // Soft delete

        return response()->json('Arrest deleted successfully.');
    }

    /**
     * Bulk soft delete selected arrests.
     */
    public function batchDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:arrests,id',
        ]);

        Arrest::whereIn('id', $request->ids)->delete(); // Soft delete

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
        $arrest->restore();

        return response()->json('Arrest restored successfully.');
    }

    /**
     * Bulk restore selected arrests.
     */
    public function bulkRestore(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:arrests,id',
        ]);

        Arrest::withTrashed()->whereIn('id', $request->ids)->restore();

        return response()->json([
            'message' => 'Selected arrests restored successfully.',
        ]);
    }
}
