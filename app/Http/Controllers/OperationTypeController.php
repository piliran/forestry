<?php

namespace App\Http\Controllers;

use App\Models\OperationType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OperationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $operationTypes = OperationType::all();

        return Inertia::render('Operations/Type', [
            'operationTypes' => $operationTypes,
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
            'description' => 'nullable|string',
        ]);

        $operationType = OperationType::create($request->all());

        return response()->json($operationType, 201); // Return the created operation type
    }

    /**
     * Display the specified resource.
     */
    public function show(OperationType $operationType)
    {
        return Inertia::render('OperationTypes/Show', [
            'operationType' => $operationType,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OperationType $operationType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OperationType $operationType)
    {
        $operationType =OperationType::find($request->id);
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $operationType->update($request->all());

        return response()->json($operationType); // Return the updated operation type
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OperationType $operationType,$id)
    {
        $operationType = OperationType::find($id);

        $operationType->delete();

        return response()->json('Operation Type deleted successfully.');
        // Uncomment the next line if redirecting to an index route:
        // return redirect()->route('operation-types.index')->with('success', 'Operation Type deleted successfully.');
    }

    /**
     * Bulk delete specified resources from storage.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);

        OperationType::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => 'Selected operation types deleted successfully.',
        ]);
    }
}
