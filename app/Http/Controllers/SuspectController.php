<?php

namespace App\Http\Controllers;

use App\Models\Suspect;
use App\Models\Country;
use App\Models\District;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SuspectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suspects = Suspect::with('district')->get(); 
      
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
        // Return a page for creating a new suspect
        return Inertia::render('Admin/CreateSuspect');
    }

  

    public function store(Request $request)
    {
    
        
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
            'suspect_photo_path' => $validated['suspect_photo_path'] ?? null, // Use null if no photo provided
        ]);
        $suspect->load('district');
    
        return response()->json($suspect, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Suspect $suspect)
    {
        $suspect->load(['country', 'district']); // Load relationships
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
    

    \Log::info('Request id:', $request->id);
    // \Log::info('Request Data:', $request->all());
    // \Log::info('Files:', $request->file() ?: []);
    // \Log::info('Raw Content:', ['content' => $request->getContent()]);
    

    $suspect = Suspect::findOrFail($id);

    // Validate the request data
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'national_id' => 'required|string|max:255|unique:suspects,national_id',  
        'district_id' => 'required|exists:districts,id',
        'village' => 'required|string|max:255',
        'TA' => 'required|string|max:255',
        'suspect_photo_path' => 'nullable|image|max:2048',
    ]);

    // Handle file upload if a new photo is provided
    if ($request->hasFile('suspect_photo_path')) {
        // Delete the old photo if it exists
        if ($suspect->suspect_photo_path) {
            Storage::disk('public')->delete($suspect->suspect_photo_path);
        }

        // Store the new photo
        $validated['suspect_photo_path'] = $request
            ->file('suspect_photo_path')
            ->store('suspects/photos', 'public');
    }

    // Update the suspect data
    $suspect->update([
        'name' => $validated['name'],
        'national_id' => $validated['national_id'],
        'district_id' => $validated['district_id'],
        'village' => $validated['village'],
        'TA' => $validated['TA'],
        'suspect_photo_path' => $validated['suspect_photo_path'] ?? $suspect->suspect_photo_path,
    ]);

    // Reload the relationship for the response
    $suspect->load('district');

    // Return the updated suspect
    return response()->json($suspect, 200);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Suspect $suspect)
    {
        $suspect->delete();

        return response()->json(['message' => 'Suspect deleted successfully'], 200);
    }

    /**
     * Batch delete specified resources from storage.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        Suspect::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Selected suspects deleted successfully'], 200);
    }


    public function updateSuspect(Request $request)
    {
        // Find the suspect by ID
        $suspect = Suspect::findOrFail($request->id);
    
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'national_id' => 'required|string|max:255',  
            'district_id' => 'required|exists:districts,id',
            'village' => 'required|string|max:255',
            'TA' => 'required|string|max:255',
            'suspect_photo_path' => 'nullable|image|max:2048',
        ]);
    
        // If a new photo is provided, handle the file upload
        if ($request->hasFile('suspect_photo_path')) {
            // Delete the old photo if it exists
            if ($suspect->suspect_photo_path) {
                Storage::disk('public')->delete($suspect->suspect_photo_path);
            }
    
            // Store the new photo
            $validated['suspect_photo_path'] = $request
                ->file('suspect_photo_path')
                ->store('suspects/photos', 'public');
        } else {
            // If no new photo is uploaded, keep the existing path
            $validated['suspect_photo_path'] = $suspect->suspect_photo_path;
        }
    
        // Update the suspect data
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

       
        // Reload the relationship for the response
        $suspect->load('district');  
    
        // Generate the correct URL for the image if a new photo was uploaded
        if ($validated['suspect_photo_path']) {
            $suspect->suspect_photo_url = asset('storage/' . $validated['suspect_photo_path']);
        } else {
            $suspect->suspect_photo_url = asset('storage/' . $suspect->suspect_photo_path);
        }
    
        // Return the updated suspect
        return response()->json($suspect, 200);
    }
    
    
}
