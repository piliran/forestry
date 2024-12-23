<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\District;
use App\Models\Staff;
use App\Models\Area;
use App\Models\Operation;


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
        // Load the zone along with its related data
        $zone->load(['department', 'contactPerson']);

        // Calculate the station count for all districts within the zone
        $stationCount = Station::whereHas('district', function ($query) use ($zone) {
            $query->where('zone_id', $zone->id); // Match districts to the zone
        });

        // Calculate the total operations count for all stations in the zone
        $operationsCount = Operation::whereHas('station.district', function ($query) use ($zone) {
            $query->where('zone_id', $zone->id);
        });

        // Calculate the total areas count for all stations in the zone
        $areasCount = Area::whereHas('station.district', function ($query) use ($zone) {
            $query->where('zone_id', $zone->id);
        });

        // Fetch staff list related to the zone via stations
        $staffList = Staff::with(['level', 'user.roles'])
            ->whereHas('station.district', function ($query) use ($zone) {
                $query->where('zone_id', $zone->id);
            })
            ->whereNull('deleted_at')
            ->get();

        // Add staff count
        $zone->staff_count = $staffList->count();
        $zone->station_count = $stationCount->count();
        $zone->operations_count = $operationsCount->count();
        $zone->areas_count = $areasCount->count();

        return Inertia::render('Zone/Show', [
            'zone' => $zone,
            'staffList' => $staffList,
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
