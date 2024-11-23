<?php
namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\District;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class StationController extends Controller
{
    public function index()
    {
        $stations = Station::with('district')->get();
        $districts = District::all();
    

        return Inertia::render('Department/Stations', [
            'stations' => $stations,
            'districts' => $districts,
        ]);

    }

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

    public function destroy(Station $station)
    {
        $station->delete();

        return response()->json(['message' => 'Station deleted'], 200);
    }

    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        Station::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Stations deleted'], 200);
    }
}
