<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Models\OperationType;
use App\Models\Station;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $operations = Operation::with(['operationType', 'station'])->get();
        $operationTypes = OperationType::all();
        $stations = Station::all();

        return Inertia::render('Operations/List', [
            'operations' => $operations,
            'operationTypes' => $operationTypes,
            'stations' => $stations,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'operation_type_id' => 'required|exists:operation_types,id',
            'station_id' => 'required|exists:stations,id',
        ]);

        DB::beginTransaction();
        try {
            $operation = Operation::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'operation_type_id' => $request->input('operation_type_id'),
                'station_id' => $request->input('station_id'),
            ]);

            DB::commit();

            $operation->load(['operationType', 'station']);

            return response()->json($operation, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create operation.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Operation $operation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'operation_type_id' => 'required|exists:operation_types,id',
            'station_id' => 'required|exists:stations,id',
        ]);

        $operation->update($request->only([
            'name',
            'description',
            'operation_type_id',
            'station_id',
        ]));

        $operation->load(['operationType', 'station']);

        return response()->json($operation, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Operation $operation)
    {
        $operation->delete();

        return response()->json(['message' => 'Operation deleted successfully'], 200);
    }

    /**
     * Batch delete specified resources from storage.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:operations,id',
        ]);

        Operation::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Operations deleted successfully'], 200);
    }
}
