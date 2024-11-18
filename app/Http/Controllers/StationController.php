<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StationController extends Controller
{
 /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stations = Station::all();
        return Inertia::render('Stations/Index', [
            'stations' => $stations,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Stations/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'chairperson' => 'required|string|max:255',
        ]);

        $station = Station::create($request->all());

        return response()->json($station, 201); // Return the created station
    }

    /**
     * Display the specified resource.
     */
    public function show(Station $station)
    {
        return Inertia::render('Stations/Show', [
            'station' => $station,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Station $station)
    {
        return Inertia::render('Stations/Edit', [
            'station' => $station,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Station $station)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'chairperson' => 'required|string|max:255',
        ]);

        $station->update($request->all());

        return response()->json($station); // Return the updated station
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Station $station)
    {
        $station->delete();

        return response()->json('Station deleted successfully.');
    }

    /**
     * Bulk delete selected stations.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:stations,id',
        ]);

        Station::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => 'Selected stations deleted successfully.',
        ]);
    }
}
