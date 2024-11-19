<?php
namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Station;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $areas = Area::with('station')->get();
        return Inertia::render('Admin/Area', [
            'areas' => $areas,
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
            'station_id' => 'required|exists:stations,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'coordinates' => 'required|string|max:255',
            'chairperson' => 'required|string|max:255',
        ]);

        $area = Area::create($request->all());

        return response()->json($area, 201); // Return the created area
    }

    /**
     * Display the specified resource.
     */
    public function show(Area $area)
    {
        return Inertia::render('Areas/Show', [
            'area' => $area->load('station'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Area $area)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Area $area)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'coordinates' => 'required|string|max:255',
            'chairperson' => 'required|string|max:255',
        ]);

        $area->update($request->all());

        return response()->json($area); // Return the updated area
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {
        $area->delete();

        return response()->json('Area deleted successfully.');
    }

    /**
     * Bulk delete selected areas.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:areas,id',
        ]);

        Area::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => 'Selected areas deleted successfully.',
        ]);
    }
}
