<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Models\OperationType;
use App\Models\Station;
use App\Models\Route;
use App\Models\Funder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch non-deleted operations
        $operations = Operation::with(['type', 'station', 'funder'])->whereNull('deleted_at')->get();
        $types = OperationType::all();
        $stations = Station::all();
        $routes = Route::all();
        $funders = Funder::all();
        return Inertia::render('Operations/List', [
            'operations' => $operations,
            'types' => $types,
            'stations' => $stations,
            'routes' => $routes,
            'funders' => $funders,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'operation_type_id' => 'required|exists:operation_types,id',
            'station_id' => 'required|exists:stations,id',    
            'date_of_operation' => 'nullable|string',
            'funded_by' => 'required|exists:funders,id',
        ]);

        $operation = Operation::create($validated);
        // $operation->load('Type');
        // $operation->load('Station');
        // $operation->load('Route');
        $operation->load(['station','route',]);

        return response()->json($operation, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Operation $operation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'operation_type_id' => 'required|exists:operation_types,id',
            'station_id' => 'required|exists:stations,id',    
            'type_of_patrol' => 'nullable|string',
            'date_of_operation' => 'nullable|string',
            'route_id' => 'required|exists:routes,id',
            'date_time_of_deployment' => 'nullable|string',
            'date_time_of_withdrawal' => 'nullable|string',
            'team_leader' => 'nullable|string',
            'funded_by' => 'nullable|string',
        ]);

        $operation->update($validated);
        $operation->load(['type', 'station','route','funder',]);
        // $operation->load('Station');
        // $operation->load('Route');

        return response()->json($operation, 200);
    }

    /**
     * Soft delete the specified resource.
     */
    public function destroy(Operation $operation)
    {
        $operation->delete(); // Soft delete

        return response()->json(['message' => 'Operation soft-deleted successfully'], 200);
    }

    /**
     * Batch soft delete specified resources from storage.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:operations,id',
        ]);

        Operation::whereIn('id', $validated['ids'])->delete(); // Soft delete

        return response()->json(['message' => 'Operations soft-deleted successfully'], 200);
    }

    /**
     * Restore a soft-deleted operation.
     */
    public function restore($id)
    {
        $operation = Operation::withTrashed()->findOrFail($id);
        $operation->restore();

        return response()->json(['message' => 'Operation restored successfully'], 200);
    }

    /**
     * Bulk restore selected soft-deleted operations.
     */
    public function bulkRestore(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        Operation::withTrashed()->whereIn('id', $validated['ids'])->restore();

        return response()->json(['message' => 'Selected Operations restored successfully'], 200);
    }

    /**
     * Fetch all soft-deleted operations.
     */
    public function trashed()
    {
        $trashedOperations = Operation::onlyTrashed()->get();

        return response()->json($trashedOperations, 200);
    }
}
