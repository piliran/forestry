<?php
namespace App\Http\Controllers;

use App\Models\Confiscate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConfiscateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $confiscates = Confiscate::all();
        return Inertia::render('Confiscates/Index', [
            'confiscates' => $confiscates,
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
            'name' => 'required|string|max:255',
            'national_id' => 'required|string|max:255',
            'village' => 'required|string|max:255',
            'TA' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'coordinates' => 'required|string|max:255',
            'proof' => 'required|string|max:255',
        ]);

        $confiscate = Confiscate::create($request->all());

        return response()->json($confiscate, 201); // Return the created confiscate
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
    public function update(Request $request, Confiscate $confiscate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'national_id' => 'required|string|max:255',
            'village' => 'required|string|max:255',
            'TA' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'coordinates' => 'required|string|max:255',
            'proof' => 'required|string|max:255',
        ]);

        $confiscate->update($request->all());

        return response()->json($confiscate); // Return the updated confiscate
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Confiscate $confiscate)
    {
        $confiscate->delete();

        return response()->json('Confiscate deleted successfully.');
    }

    /**
     * Bulk delete selected confiscates.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:confiscates,id',
        ]);

        Confiscate::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => 'Selected confiscates deleted successfully.',
        ]);
    }
}
