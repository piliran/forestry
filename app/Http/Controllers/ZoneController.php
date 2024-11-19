<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;


class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $zones = Zone::with('department')->get();
        $departments = Department::all();
        return Inertia::render('Admin/Zone', [
            'zones' => $zones,
            'departments' => $departments,
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'department_id' => 'nullable|integer',
        
        ]);

        $zone = Zone::create($validated);

        $zone->load('department');


        return response()->json($zone, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Zone $zone)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Zone $zone)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Zone $zone)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'department_id' => 'nullable|integer',
            'location' => 'nullable|string|max:255',
        
        ]);

        $zone->update($request->all());
        $zone->load('department');


        return response()->json($zone);
    }

    /**
     * Remove the specified resource from storage.
     */
  

    public function destroy(Zone $zone)
    {
        $zone->delete();

        return response()->json('zone deleted successfully.');
    }

    /**
     * Bulk delete selected users.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);

        Zone::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => 'Selected zones deleted successfully.',
        ]);
    }
}
