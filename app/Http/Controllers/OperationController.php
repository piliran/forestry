<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $operations = Operation::all();
        return Inertia::render('Operations/Index', [
            'operations' => $operations,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Operations/Create');
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

        $operation = Operation::create($request->all());

        return response()->json($operation, 201); // Return the created operation
    }

    /**
     * Display the specified resource.
     */
    public function show(Operation $operation)
    {
        return Inertia::render('Operations/Show', [
            'operation' => $operation,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Operation $operation)
    {
        return Inertia::render('Operations/Edit', [
            'operation' => $operation,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Operation $operation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $operation->update($request->all());

        return response()->json($operation); // Return the updated operation
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Operation $operation)
    {
        $operation->delete();

        return response()->json('Operation deleted successfully.');
    }

    /**
     * Bulk delete selected operations.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:operations,id', // Ensure each id exists in the database
        ]);

        Operation::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => 'Selected operations deleted successfully.',
        ]);
    }
}
