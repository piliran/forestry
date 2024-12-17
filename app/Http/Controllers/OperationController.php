<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Models\OperationType;
use App\Models\Station;
use App\Models\Route;
use App\Models\Funder;
use App\Models\FunderToOperation;
use App\Models\RouteToOperation;

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
        $operations = Operation::with(['operationType', 'station', 'funder', 'route'])
            ->whereNull('deleted_at')
            ->get();
        $operationTypes = OperationType::all();
        $stations = Station::all();
        $routes = Route::all();
        $funders = Funder::all();

        return Inertia::render('Operations/List', [
            'operations' => $operations,
            'operationTypes' => $operationTypes,
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
        Gate::authorize('create', Operation::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'operation_type_id' => 'nullable|exists:operation_types,id',
            'station_id' => 'required|exists:stations,id',
            'route_id' => 'required|exists:routes,id',
            'funded_by' => 'required|exists:funders,id',
        ]);

        $validated['created_by'] = auth()->id();

        $operation = Operation::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'operation_type_id' => $validated['operation_type_id'],
            'station_id' => $validated['station_id'],
            'created_by' => auth()->id(),
        ]);

       FunderToOperation::create([
            'funded_by' => $validated['funded_by'],
            'operation_id' => $operation->id,

        ]);

        RouteToOperation::create([
            'route_id' => $validated['route_id'],
            'operation_id' => $operation->id,

        ]);

        $operation->load(['operationType', 'station', 'funder', 'route']);

        return response()->json($operation, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Operation $operation)
    {

        $operation = Operation::findOrFail($request->id);

        Gate::authorize('update', $operation);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'operation_type_id' => 'required|exists:operation_types,id',
            'station_id' => 'required|exists:stations,id',
            'route_id' => 'required|exists:routes,id',
            'funded_by' => 'required|exists:funders,id',
        ]);

        $operation->update($validated);
        $operation->load(['operationType', 'station', 'funder', 'route']);

        return response()->json($operation, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Operation $operation)
    {
        $operation->load(['station', 'operationType', 'funder', 'operationToTeam']);

        return Inertia::render('Operation/Show', [
            'operation' => $operation,
        ]);
    }

    /**
     * Soft delete the specified resource.
     */
    public function destroy(Operation $operation)
    {
        Gate::authorize('delete', $operation);

        $operation->delete();

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

        Operation::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Operations soft-deleted successfully'], 200);
    }

    /**
     * Restore a soft-deleted operation.
     */
    public function restore($id)
    {
        $operation = Operation::withTrashed()->findOrFail($id);
        Gate::authorize('restore', $operation);

        $operation->restore();

        return response()->json(['message' => 'Operation restored successfully'], 200);
    }

    /**
     * Bulk restore selected soft-deleted operations.
     */
    public function bulkRestore(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:operations,id',
        ]);

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
