<?php

namespace App\Http\Controllers;

use App\Models\SuspectToOperation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class SuspectToOperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $suspectToOperations = SuspectToOperation::all();
        return Inertia::render('SuspectToOperation/Index', [
            'suspectToOperations' => $suspectToOperations,
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
        Gate::authorize('create', new SuspectToOperation());


       $request->validate([
            'suspect_id' => 'required|exists:suspects,id',
            'operation_id' => 'required|exists:operations,id'
        ]);

        $suspectToOperqation = SuspectToOperation::create($request->all());

        return response()->json($suspectToOperqation);
    }

    /**
     * Display the specified resource.
     */
    public function show(SuspectToOperation $suspectToOperation)
    {
        //
        $suspectToOperation->load(['suspect', 'operstion']);
        return Inertia::render('SuspectToOperation',[
            'suspectToOperation' => $suspectToOperation
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuspectToOperation $suspectToOperation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SuspectToOperation $suspectToOperation)
    {
        Gate::authorize('update', $suspectToOperation);

        $validated = $request->validate([
            'suspect_id' => 'required|exists:suspects,id',
            'operation_id' => 'required|exists:operations,id',
        ]);

        $suspectToOperation->update($validated);
        return response()->json($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuspectToOperation $suspectToOperation)
    {
        Gate::authorize('delete', $suspectToOperation);

        $suspectToOperation->delete();

        return response()->json(['message'=>'Record deleted Successfully']);
    }

    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        SuspectToOperation::whereIn('id', $validated['ids'])->delete(); // Soft delete

        return response()->json(['message' => 'SuspectToOperation soft-deleted'], 200);
    }

    public function restore($id)
    {
        $suspectToOperation = SuspectToOperation::withTrashed()->findOrFail($id);
        $suspectToOperation->restore(); // Restore soft-deleted suspectToOperation$suspectToOperation

        return response()->json(['message' => 'SuspectToOperation restored successfully.'], 200);
    }

    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        SuspectToOperation::withTrashed()->whereIn('id', $request->ids)->restore(); // Restore soft-deleted suspectToOperation

        return response()->json(['message' => 'Selected suspectToOperation restored successfully.'], 200);
    }

    public function trashed()
    {
        $trashedSuspectToConfiscate = SuspectToOperation::onlyTrashed()->get(); // Fetch all soft-deleted suspectToOperation

        return response()->json($trashedSuspectToConfiscate, 200);
    }
}
