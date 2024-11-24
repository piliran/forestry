<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\District;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch non-deleted stations
        $stations = Station::with('district')->whereNull('deleted_at')->get();
        $districts = District::all();

        return Inertia::render('Stations/Index', [
            'stations' => $stations,
            'districts' => $districts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
            'email' => 'required|email|max:255',
            'chairperson' => 'required|string|max:255',
        ]);

        $station = Station::create($request->all());
        $station->load('district');

        return response()->json($station, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Station $station)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
            'email' => 'required|email|max:255',
            'chairperson' => 'required|string|max:255',
        ]);

        $station->update($validated);
        $station->load('district');

        return response()->json($station, 200);
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy(Station $station)
    {
        $station->delete();  // Soft delete

        return response()->json(['message' => 'Station soft-deleted successfully.'], 200);
    }

    /**
     * Bulk soft delete selected stations.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        Station::whereIn('id', $validated['ids'])->delete();  // Soft delete

        return response()->json(['message' => 'Selected stations soft-deleted successfully.'], 200);
    }

    /**
     * Restore a soft-deleted station.
     */
    public function restore($id)
    {
        $station = Station::withTrashed()->findOrFail($id);
        $station->restore();  // Restore the soft-deleted station

        return response()->json(['message' => 'Station restored successfully.'], 200);
    }

    /**
     * Bulk restore soft-deleted stations.
     */
    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Station::withTrashed()->whereIn('id', $request->ids)->restore();  // Restore soft-deleted stations

        return response()->json(['message' => 'Selected stations restored successfully.'], 200);
    }

    /**
     * Fetch trashed stations.
     */
    public function trashed()
    {
        $trashedStations = Station::onlyTrashed()->get();  // Fetch all soft-deleted stations

        return response()->json($trashedStations, 200);
    }
}
