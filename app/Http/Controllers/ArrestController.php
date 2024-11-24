<?php

namespace App\Http\Controllers;

use App\Models\Arrest;
use App\Models\Confiscate;
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
        $arrests = Arrest::with('confiscate')->whereNull('deleted_at')->get();
        return Inertia::render('Arrests/Index', [
            'arrests' => $arrests,
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
        $request->validate([
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'proof' => 'required|string|max:255',
            'confiscate_id' => 'required|exists:confiscates,id',
        ]);

        $arrest = Arrest::create($request->all());

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

        $request->validate([
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'proof' => 'required|string|max:255',
            'confiscate_id' => 'required|exists:confiscates,id',
        ]);

        $arrest->update($request->all());

        return response()->json($arrest); // Return the updated arrest
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy($id)
    {
        $arrest = Arrest::findOrFail($id);
        $arrest->delete(); // Soft delete

        return response()->json('Arrest deleted successfully.');
    }

    /**
     * Bulk soft delete selected arrests.
     */
    public function bulkDelete(Request $request)
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
