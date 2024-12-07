<?php

namespace App\Http\Controllers;

use App\Models\OperationType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class OperationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch non-deleted operation types
        $operationTypes = OperationType::whereNull('deleted_at')->get();

        return Inertia::render('Operations/Type', [
            'operationTypes' => $operationTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', new OperationType());

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
     * Update the specified resource in storage.
     */
    public function update(Request $request, OperationType $operationType)
    {
        Gate::authorize('update', $operationType);

        $operationType = OperationType::find($request->id);
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $operationType->update($request->all());

        return response()->json($operationType); // Return the updated operation type
    }

    /**
     * Soft delete the specified resource.
     */
    public function destroy(OperationType $operationType)
    {
        Gate::authorize('delete', $operationType);

        $operationType->delete(); // Soft delete

        return response()->json('Operation Type soft-deleted successfully.');
    }

    /**
     * Batch soft delete specified resources from storage.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:operation_types,id',
        ]);

        OperationType::whereIn('id', $validated['ids'])->delete(); // Soft delete

        return response()->json([
            'message' => 'Selected operation types soft-deleted successfully.',
        ]);
    }

    /**
     * Restore a soft-deleted operation type.
     */
    public function restore($id)
    {
        $operationType = OperationType::withTrashed()->findOrFail($id);
        $operationType->restore();

        return response()->json(['message' => 'Operation Type restored successfully.'], 200);
    }

    /**
     * Bulk restore selected soft-deleted operation types.
     */
    public function bulkRestore(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        OperationType::withTrashed()->whereIn('id', $validated['ids'])->restore();

        return response()->json(['message' => 'Selected operation types restored successfully.'], 200);
    }

    /**
     * Fetch all soft-deleted operation types.
     */
    public function trashed()
    {
        $trashedOperationTypes = OperationType::onlyTrashed()->get();

        return response()->json($trashedOperationTypes, 200);
    }
}
