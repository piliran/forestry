<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Models\OperationType;
use App\Models\Station;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $operations = Operation::with('Type')->get();
        $types = OperationType::all();
        $stations = Station::all();

        return Inertia::render('Operations/List', [
            'operations' => $operations,
            'types' => $types,
            'stations' => $stations,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated=  $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'operation_type_id' => 'required|exists:operation_types,id',
    
        ]);

       
        $operations = Operation::create($request->all());
        $operations->load('Type');

        return response()->json($operations, 201);

       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Operation $operation)
    {

        $operation = Operation::find($request->id);

        $validated= $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'operation_type_id' => 'required|exists:operation_types,id',
           
        ]);

        $operation->update($validated);

        
        $operation->load('Type');

        return response()->json($operation, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Operation $operation,$id)
    {
        $operation = Operation::find($id);

        $operation->delete();

        return response()->json(['message' => 'Operation deleted successfully'], 200);
    }
// plann sec
// desk officer
// zone manager
// law enforce 

// natura res man team lead
// forestry specialist

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
