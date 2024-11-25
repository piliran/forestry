<?php

namespace App\Http\Controllers;

use App\Models\Confiscate;
use App\Models\Encroached;
use App\Models\Suspect;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate; // Preserved for future use
use App\Models\User; // Preserved for future use

class ConfiscateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch only non-deleted confiscates
        
        $confiscates = Confiscate::whereNull('deleted_at')->with(['suspect','encroached',])->get();
        $suspects = Suspect::all();
        $encroached_areas = Encroached::all();

        return Inertia::render('Department/Confiscates', [
            'confiscates' => $confiscates,
            'suspects' => $suspects,
            'encroached_areas' => $encroached_areas,
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
            'item' => 'required|string|max:255',
            'quantity' => 'required|numeric|max:255',
            'suspect_id' => 'required|exists:suspects,id',
            'encroached_area_id' => 'required|exists:encroacheds,id',
            'proof' => 'required|string|max:255',
        ]);

        $confiscate = Confiscate::create($request->all());
        $confiscate->load('suspect');
        $confiscate->load('encroached_area');


        return response()->json($confiscate, 201); // Return the created confiscate
    }

    /**
     * Display the specified resource.
     */
    public function show(Confiscate $confiscate)
    {
        return Inertia::render('Confiscates/Show', [
            'confiscate' => $confiscate,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Confiscate $confiscate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Confiscate $confiscate)
    {
        
        $request->validate([
            'item' => 'required|string|max:255',
            'quantity' => 'required|numeric|max:255',
            'suspect_id' => 'required|exists:suspects,id',
            'encroached_area_id' => 'required|exists:encroacheds,id',
            'proof' => 'required|string|max:255',
        ]);

        $confiscate->update($request->all());
        $confiscate->load(['suspect','encroached_area',]);

        return response()->json($confiscate, 200); // Return the createdupdated


    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy($id)
    {
        $confiscate = Confiscate::findOrFail($id);
        $confiscate->delete(); // Soft delete

        return response()->json('Confiscate deleted successfully.');
    }

    /**
     * Bulk soft delete selected confiscates.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:confiscates,id',
        ]);

        Confiscate::whereIn('id', $request->ids)->delete(); // Soft delete

        return response()->json([
            'message' => 'Selected confiscates deleted successfully.',
        ]);
    }

    /**
     * Restore a soft-deleted confiscate.
     */
    public function restore($id)
    {
        $confiscate = Confiscate::withTrashed()->findOrFail($id);
        $confiscate->restore();

        return response()->json('Confiscate restored successfully.');
    }

    /**
     * Bulk restore selected confiscates.
     */
    public function bulkRestore(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:confiscates,id',
        ]);

        Confiscate::withTrashed()->whereIn('id', $request->ids)->restore();

        return response()->json([
            'message' => 'Selected confiscates restored successfully.',
        ]);
    }
}
