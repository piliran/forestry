<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch non-deleted zones, including related departments
        $zones = Zone::with('department','contactPerson')->whereNull('deleted_at')->get();
        $departments = Department::all();

        return Inertia::render('Zone/Index', [
            'zones' => $zones,
            'departments' => $departments,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Implementation if necessary
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', new Zone());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'department_id' => 'nullable|integer',
            'contact_person' => 'required|exists:users,id',
        ]);

        // Create a new zone and associate the department
        $zone = Zone::create($validated);

        // Load the associated department
        $zone->load('department');

        return response()->json($zone, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Zone $zone)
    {
        // Implementation if necessary
        $zone->load('department');
        return Inertia::render('Zone/Show',[
            'department' => $zone,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Zone $zone)
    {
        // Implementation if necessary
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Zone $zone)
    {
        Gate::authorize('update', $zone);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'department_id' => 'nullable|integer',
            'location' => 'nullable|string|max:255',
            'contact_person' => 'required|exists:users,id',
        ]);

        // Update the zone data
        $zone->update($validated);

        // Load the associated department
        $zone->load('department');

        return response()->json($zone);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Zone $zone)
    {
        Gate::authorize('delete', $zone);

        // Perform a soft delete by setting 'deleted_at'
        $zone->delete();

        return response()->json('Zone deleted successfully.');
    }

    /**
     * Bulk delete selected zones.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);

        // Soft delete the zones in a batch
        Zone::whereIn('id', $validated['ids'])->delete();

        return response()->json([
            'message' => 'Selected zones deleted successfully.',
        ]);
    }
}
