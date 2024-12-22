<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\District;
use App\Models\Staff;
use App\Models\User;

use App\Models\Operation;
use App\Models\Area;
use App\Models\StaffToStation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch non-deleted stations
        $stations = Station::with('district','contactPerson')->whereNull('deleted_at')->get();
        $districts = District::all();
        $users = User::with(['roles', 'district', 'privileges'])
        ->whereNull('deleted_at')
        ->get();


        return Inertia::render('Stations/Index', [
            'stations' => $stations,
            'districts' => $districts,
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', new Station());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
            'email' => 'required|email|max:255',
            'contact_person' => 'required|exists:users,id',
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
        Gate::authorize('update', $station);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
            'email' => 'required|email|max:255',
            'contact_person' => 'required|exists:users,id',
        ]);

        $station->update($validated);
        $station->load('district');

        return response()->json($station, 200);
    }

    // public function show(Station $station)
    // {
    //     $station->load(['area', 'district', 'operations', 'contactPerson']);
    //     return Inertia::render('Stations/Show', [
    //         'station' => $station
    //     ]);
    // }

    public function show(Station $station)
    {
        $station->load([
            'area',
            'district',
            'operations',
            'contactPerson'
        ]);

        $staffList = Staff::with(['level', 'user.roles'])
        ->whereHas('station', function ($query) use ($station) {
            $query->where('stations.id', $station->id);
        })
        ->whereNull('deleted_at')
        ->get();


        $station->staff_count = $staffList->count();
        $station->operations_count = $station->operations()->count();
        $station->areas_count = $station->area()->count();

        return Inertia::render('Stations/Show', [
            'station' => $station,
            'staffList' => $staffList,
        ]);
    }



    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy(Station $station)
    {
        Gate::authorize('delete', $station);

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
