<?php
namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Zone;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DistrictController extends Controller
{
    public function index()
    {
        $districts = District::with('zone')->get();
        $zones = Zone::all();

        return Inertia::render('Admin/District', [
            'districts' => $districts,
            'zones' => $zones,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'name' => 'required|string|max:255',

        ]);

        $district = District::create($validated);
        $district->load('zone');


        return response()->json($district, 201);
    }

    public function update(Request $request, District $district)
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'name' => 'required|string|max:255',
        
        ]);

        $district->update($validated);
        $district->load('zone');

        return response()->json($district, 200);
    }

    public function destroy(District $district)
    {
        $district->delete();

        return response()->json(['message' => 'District deleted'], 200);
    }

    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        District::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Districts deleted'], 200);
    }
}
