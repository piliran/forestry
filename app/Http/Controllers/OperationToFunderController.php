<?php

namespace App\Http\Controllers;

use App\Models\OperationToFunder;
use App\Models\Operation;
use App\Models\Funder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OperationToFunderController extends Controller
{
    /**
     * Display a listing of operations and their associated funders.
     */
    public function index()
    {
        $associations = OperationToFunder::with(['operation', 'funder'])
            ->whereNull('deleted_at')
            ->get();

        $operations = Operation::all();
        $funders = Funder::all();

        return Inertia::render('OperationFunders/Index', [
            'associations' => $associations,
            'operations' => $operations,
            'funders' => $funders,
        ]);
    }

    /**
     * Store a newly created association in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'operation_id' => 'required|exists:operations,id',
            'funder_id' => 'required|exists:funders,id',
        ]);

        DB::beginTransaction();
        try {
            $association = OperationToFunder::create($validated);

            DB::commit();
            $association->load(['operation', 'funder']);
            return response()->json($association, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create association.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified association in the database.
     */
    public function update(Request $request, OperationToFunder $operationToFunder)
    {
        $validated = $request->validate([
            'operation_id' => 'required|exists:operations,id',
            'funder_id' => 'required|exists:funders,id',
        ]);

        $operationToFunder->update($validated);
        $operationToFunder->load(['operation', 'funder']);

        return response()->json($operationToFunder, 200);
    }

    /**
     * Soft delete the specified association.
     */
    public function destroy(OperationToFunder $operationToFunder)
    {
        $operationToFunder->delete();

        return response()->json(['message' => 'Association soft-deleted'], 200);
    }

    /**
     * Batch soft delete associations.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        OperationToFunder::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Associations soft-deleted'], 200);
    }

    /**
     * Restore a soft-deleted association.
     */
    public function restore($id)
    {
        $operationToFunder = OperationToFunder::withTrashed()->findOrFail($id);
        $operationToFunder->restore();

        return response()->json(['message' => 'Association restored successfully.'], 200);
    }

    /**
     * Bulk restore soft-deleted associations.
     */
    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        OperationToFunder::withTrashed()->whereIn('id', $request->ids)->restore();

        return response()->json(['message' => 'Selected associations restored successfully.'], 200);
    }

    /**
     * Display all soft-deleted associations.
     */
    public function trashed()
    {
        $trashed = OperationToFunder::onlyTrashed()->with(['operation', 'funder'])->get();

        return response()->json($trashed, 200);
    }
}
