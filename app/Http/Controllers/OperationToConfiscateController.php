<?php

namespace App\Http\Controllers;

use App\Models\OperationToConfiscate;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Inertia\Inertia;

class OperationToConfiscateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $operationToConfiscate = OperationToConfiscate::all();
        return Inertia::render('OperationToConfiscate/Index', [
            'operationToConfiscate' => $operationToConfiscate
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validate = $request->validate([
            'suspect_id' => 'required|exists:suspects, id',
            'operation_id' => 'required|exists:operations, id'
        ]);

        DB::beginTransaction();
        try{
            $operationToConfiscate = OperationToConfiscate::create($request->only('operation_id', 'confiscate_id'));
            DB::commit();

            return response()->json($operationToConfiscate, 201);

        }catch(\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create record',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(OperationToConfiscate $operationToConfiscate)
    {
        //
        $operationToConfiscate->load(['operation', 'confiscate']);

        return Inertia::render('OperationToConfiscate',[
            'operationToConfiscate' => $operationToConfiscate
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OperationToConfiscate $operationToConfiscate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OperationToConfiscate $operationToConfiscate)
    {
        //
        $validated = $request->validate([
            'operation_id' => 'required|exists:operations, id',
            'confiscate_id' => 'required|exists:confiscates,id'
        ]);

        DB::beginTransaction();

        try{
            $operationToConfiscate->update($request->only('operation_id', 'confiscate_id'));
            DB::commit();

            return response()->json($operationToConfiscate, 200);
        }catch(\Exception $e){
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to update record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OperationToConfiscate $operationToConfiscate)
    {
        //
        $operationToConfiscate->delete();

        return response()->json(['Message' => 'Record deleted successfully'], 200);
    }
}
