<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Encroached;
use Illuminate\Http\Request;
use Inertia\Inertia;


class EncroachedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $encroaches = Encroached::with('area')->get();
        $areas = Area::all();
        return Inertia::render('Department/EncroachedAreas', [
            'encroaches' => $encroaches,
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
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'latitude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',
        
        ]);

        $encroached = Encroached::create($validated);

        $encroached->load('area');


        return response()->json($encroached, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Encroached $encroached)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Encroached $encroached)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Encroached $encroached)
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'latitude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',
        
        ]);

        
        $encroached->update($request->all());
        $encroached->load('area');


        return response()->json($encroached);
    }

    /**
     * Remove the specified resource from storage.
     */
  

    public function destroy(Encroached $encroached)
    {
        $encroached->delete();

        return response()->json('Encroached area deleted successfully.');
    }

    /**
     * Bulk delete selected users.
     */
    public function batchDelete(Request $request)
    {
      
        $validated = $request->validate(['ids' => 'required|array']);
        Encroached::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Selected Encroached Areas deleted'], 200);

    }
}
