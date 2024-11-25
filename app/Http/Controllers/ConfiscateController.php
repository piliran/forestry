<?php

namespace App\Http\Controllers;

use App\Models\Confiscate;
use App\Models\Encroached;
use App\Models\Suspect;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate; // Preserved for future use
use App\Models\User; // Preserved for future use
use Illuminate\Support\Facades\Storage;

class ConfiscateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch only non-deleted confiscates
        
        $confiscates = Confiscate::whereNull('deleted_at')->with('suspect')->get();
        $suspects = Suspect::all();
        $encroached_areas = Encroached::all();
 
        return Inertia::render('Department/Confiscates', [
            'confiscates' => $confiscates,
            'suspects' => $suspects,
            'encroached_areas' => $encroached_areas,
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
        
    
        // Validate the request data
        $validated = $request->validate([
            'item' => 'required|string|max:255',
            'quantity' => 'required|numeric|max:255',
            'suspect_id' => 'required|exists:suspects,id',
            'proof' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov|max:20480', // Max 20MB
        ]);
    
        // Handle file upload for the proof field
        if ($request->hasFile('proof')) {
            $validated['proof'] = $request
                ->file('proof')
                ->store('proofs', 'public'); // Save in 'public/confiscates/proofs'
        }
    
        // Create the confiscate record
        $confiscate = Confiscate::create([
            'item' => $validated['item'],
            'quantity' => $validated['quantity'],
            'suspect_id' => $validated['suspect_id'],
            'proof' => $validated['proof'] ?? null, // Default to null if no file
        ]);
    
        // Load related suspect data
        $confiscate->load('suspect');
    
        // Return the created confiscate as JSON
        return response()->json($confiscate, 201);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Confiscate $confiscate)
    {
        return Inertia::render('Confiscates/Show', [
            'confiscate' => $confiscate,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Confiscate $confiscate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateConfiscate(Request $request)
    {
        $confiscate = Confiscate::findOrFail($request->id);
        // Validate the incoming request
        $validated = $request->validate([
            'item' => 'required|string|max:255',
            'quantity' => 'required|numeric|max:255',
            'suspect_id' => 'required|exists:suspects,id',
            'proof' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov|max:20480', // Max 20MB
        ]);
    
        // Handle file upload if a new proof file is provided
        if ($request->hasFile('proof')) {
            // Delete the old proof file if it exists
            if ($confiscate->proof) {
                Storage::disk('public')->delete($confiscate->proof);
            }
    
            // Store the new file
            $validated['proof'] = $request
                ->file('proof')
                ->store('proofs', 'public');
        }
    
        // Update the confiscate record with validated data
        $confiscate->update([
            'item' => $validated['item'],
            'quantity' => $validated['quantity'],
            'suspect_id' => $validated['suspect_id'],
          
        ]);

        
        if ($request->hasFile('proof')) {
            $confiscate->update(['proof' => $validated['proof']]);
        }
    
        // Load related suspect data
        $confiscate->load('suspect');
    
        // Return the updated confiscate as JSON
        return response()->json($confiscate, 200);
    }
    

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy($id)
    {
        $confiscate = Confiscate::findOrFail($id);
        $confiscate->delete(); // Soft delete

        return response()->json('Confiscate deleted successfully.');
    }

    /**
     * Bulk soft delete selected confiscates.
     */
    public function batchDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:confiscates,id',
        ]);

        Confiscate::whereIn('id', $request->ids)->delete(); // Soft delete

        return response()->json([
            'message' => 'Selected confiscates deleted successfully.',
        ]);
    }

    /**
     * Restore a soft-deleted confiscate.
     */
    public function restore($id)
    {
        $confiscate = Confiscate::withTrashed()->findOrFail($id);
        $confiscate->restore();

        return response()->json('Confiscate restored successfully.');
    }

    /**
     * Bulk restore selected confiscates.
     */
    public function bulkRestore(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:confiscates,id',
        ]);

        Confiscate::withTrashed()->whereIn('id', $request->ids)->restore();

        return response()->json([
            'message' => 'Selected confiscates restored successfully.',
        ]);
    }
}
