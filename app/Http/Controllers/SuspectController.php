<?php

namespace App\Http\Controllers;

use App\Models\Suspect;
use App\Models\User;
use App\Models\Country;
use App\Models\District;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class SuspectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch non-deleted suspects
        $suspects = Suspect::with('district')->whereNull('deleted_at')->get();
        $districts = District::all();

        return Inertia::render('Department/Suspect', [
            'suspects' => $suspects,
            'districts' => $districts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/CreateSuspect');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()->cannot('create', Suspect::class)) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'national_id' => 'required|string|max:255|unique:suspects,national_id',      
            'district_id' => 'required|exists:districts,id',
            'village' => 'required|string|max:255',
            'TA' => 'required|string|max:255',
            'suspect_photo_path' => 'nullable|image|max:2048', 
        ]);

        if ($request->hasFile('suspect_photo_path')) {
            $validated['suspect_photo_path'] = $request
                ->file('suspect_photo_path')
                ->store('suspects/photos', 'public'); 
        }

        $suspect = Suspect::create([
            'name' => $validated['name'],
            'national_id' => $validated['national_id'],
            'district_id' => $validated['district_id'],
            'village' => $validated['village'],
            'TA' => $validated['TA'],
            'suspect_photo_path' => $validated['suspect_photo_path'] ?? null,
        ]);
        $suspect->load('district');
    
        return response()->json($suspect, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Suspect $suspect)
    {
        $suspect->load(['country', 'district']); 
        return Inertia::render('Admin/ShowSuspect', [
            'suspect' => $suspect,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Suspect $suspect)
    {
        return Inertia::render('Admin/EditSuspect', [
            'suspect' => $suspect,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

      
        
        $suspect = Suspect::findOrFail($request->id);


        if (auth()->user()->cannot('update',  $suspect)) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'national_id' => 'required|string|max:255|unique:suspects,national_id,' . $suspect->id,  
            'district_id' => 'required|exists:districts,id',
            'village' => 'required|string|max:255',
            'TA' => 'required|string|max:255',
            'suspect_photo_path' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('suspect_photo_path')) {
            if ($suspect->suspect_photo_path) {
                Storage::disk('public')->delete($suspect->suspect_photo_path);
            }

            $validated['suspect_photo_path'] = $request
                ->file('suspect_photo_path')
                ->store('suspects/photos', 'public');
        }

        $suspect->update([
            'name' => $validated['name'],
            'national_id' => $validated['national_id'],
            'district_id' => $validated['district_id'],
            'village' => $validated['village'],
            'TA' => $validated['TA'],
            'suspect_photo_path' => $validated['suspect_photo_path'] ?? $suspect->suspect_photo_path,
        ]);

        $suspect->load('district');
    
        return response()->json($suspect, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Suspect $suspect)
    {
      
        if (auth()->user()->cannot('delete', $suspect)) {
            abort(403, 'Unauthorized action.');
        }

        
        // Perform soft delete by setting 'deleted_at'
        $suspect->delete();
        return response()->json(['message' => 'Suspect deleted successfully'], 200);
    }

    /**
     * Batch delete specified resources from storage.
     */
    public function batchDelete(Request $request)
    {
        if (auth()->user()->cannot('batchDelete', Suspect::class)) {
            abort(403, 'Unauthorized action.');
        }
    
        
        $validated = $request->validate(['ids' => 'required|array']);
        Suspect::whereIn('id', $validated['ids'])->delete();
        return response()->json(['message' => 'Selected suspects deleted successfully'], 200);
    }

    /**
     * Update a suspect's details.
     */
    public function updateSuspect(Request $request)
    {
     
        
        $suspect = Suspect::findOrFail($request->id);

        if (auth()->user()->cannot('update', $suspect)) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'national_id' => 'required|string|max:255',  
            'district_id' => 'required|exists:districts,id',
            'village' => 'required|string|max:255',
            'TA' => 'required|string|max:255',
            'suspect_photo_path' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('suspect_photo_path')) {
            if ($suspect->suspect_photo_path) {
                Storage::disk('public')->delete($suspect->suspect_photo_path);
            }

            $validated['suspect_photo_path'] = $request
                ->file('suspect_photo_path')
                ->store('suspects/photos', 'public');
        } else {
            $validated['suspect_photo_path'] = $suspect->suspect_photo_path;
        }

        $suspect->update([
            'name' => $validated['name'],
            'national_id' => $validated['national_id'],
            'district_id' => $validated['district_id'],
            'village' => $validated['village'],
            'TA' => $validated['TA'],
        ]);

        if ($request->hasFile('suspect_photo_path')) {
            $suspect->update(['suspect_photo_path' => $validated['suspect_photo_path']]);
        }

        $suspect->load('district');  

        $suspect->suspect_photo_url = $suspect->suspect_photo_path 
            ? asset('storage/' . $suspect->suspect_photo_path) 
            : null;

        return response()->json($suspect, 200);
    }
}
