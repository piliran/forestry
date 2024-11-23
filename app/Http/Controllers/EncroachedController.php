<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Encroached;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;



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
           'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        
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
        
        $Encroached = Encroached::find($request->id);
        Log::info($Encroached);

        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        
        ]);

        
        $Encroached->update($validated);
        $Encroached->load('area');


        return response()->json($Encroached, 201);
      
    }

    /**
     * Remove the specified resource from storage.
     */
  

    public function destroy(Encroached $encroached,$id)
    {
        $encroached = Encroached::find($id);

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
