<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Encroached;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class EncroachedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch non-deleted encroached areas
        $encroaches = Encroached::with('area')->whereNull('deleted_at')->get();
        $areas = Area::all();

        return Inertia::render('EncroachedAreas/Index', [
            'encroaches' => $encroaches,
            'areas' => $areas,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', new Encroached());

        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'encroachment_type' => 'required|string|max:255',
            'estimated_area' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'remarks' => 'required|string|max:255',
        ]);

        $encroached = Encroached::create($validated);
        $encroached->load('area');

        return response()->json($encroached, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Encroached $encroached)
    {
        Gate::authorize('update', $encroached);

        $encroached=Encroached::find($request->id);
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'encroachment_type' => 'required|string|max:255',
            'estimated_area' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'remarks' => 'required|string|max:255',
        ]);

        $encroached->update($validated);
        $encroached->load('area');

        return response()->json($encroached, 200);
    }

    public function show(Encroached $encroached)
    {
        $encroached->load('area');
        return Inertia::render('Encroached/Show', [
            'encroached' => $encroached
        ]);
    }

    /**
     * Soft delete the specified resource.
     */
    public function destroy(Encroached $encroached)
    {
        Gate::authorize('delete', $encroached);

        $encroached->delete(); // Soft delete

        return response()->json('Encroached area soft-deleted successfully.');
    }

    /**
     * Bulk soft delete selected encroached areas.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        Encroached::whereIn('id', $validated['ids'])->delete(); // Soft delete

        return response()->json(['message' => 'Selected Encroached Areas soft-deleted successfully.'], 200);
    }

    /**
     * Restore a soft-deleted encroached area.
     */
    public function restore($id)
    {
        $encroached = Encroached::withTrashed()->findOrFail($id);
        $encroached->restore();

        return response()->json(['message' => 'Encroached area restored successfully.'], 200);
    }

    /**
     * Bulk restore selected soft-deleted encroached areas.
     */
    public function bulkRestore(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        Encroached::withTrashed()->whereIn('id', $validated['ids'])->restore();

        return response()->json(['message' => 'Selected Encroached Areas restored successfully.'], 200);
    }

    /**
     * Fetch all soft-deleted (trashed) encroached areas.
     */
    public function trashed()
    {
        $trashedEncroaches = Encroached::onlyTrashed()->get();

        return response()->json($trashedEncroaches, 200);
    }
}
