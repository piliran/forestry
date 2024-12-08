<?php

namespace App\Http\Controllers;

use App\Models\SuspectToConfiscate;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SuspectToConfiscateController extends Controller
{
    public function index()
    {
        $suspectToConfiscates = SuspectToConfiscate::all();

        return Inertia::render('SuspectToConfiscate/Index', [
            'suspectToConfiscates' => $suspectToConfiscates,
        ]);
    }


    public function store(Request $request)
    {
        Gate::authorize('create', new SuspectToConfiscate);

        $request->validate([
            'suspect_id' => 'required|exists:suspects,id',
            'confiscate_id' => 'required|exists:confiscates,id',
            'quantity' => 'required|string',
            'reason' => 'string'
        ]);

        $suspectToConfiscates = SuspectToConfiscate::create($request->all());

        return response()->json($suspectToConfiscates);
    }


    public function update(Request $request, SuspectToConfiscate $suspectToConfiscate)
    {
        Gate::authorize('update', $suspectToConfiscate);

        $validated = $request->validate([
            'suspect_id' => 'required|exists:suspects,id',
            'confiscate_id' => 'required|exists:confiscates,id',
            'quantity' => 'required|string',
            'reason' => 'string'
        ]);

        $suspectToConfiscate->update($validated);

        return response()->json($suspectToConfiscate, 200);
    }

    public function destroy(SuspectToConfiscate $suspectToConfiscate)
    {
        Gate::authorize('delete', $suspectToConfiscate);

        $suspectToConfiscate->delete(); // Soft delete

        return response()->json(['message' => 'SuspectToConfiscate soft-deleted'], 200);
    }

    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        SuspectToConfiscate::whereIn('id', $validated['ids'])->delete(); // Soft delete

        return response()->json(['message' => 'SuspectToConfiscate soft-deleted'], 200);
    }

    public function restore($id)
    {
        $suspectToConfiscate = SuspectToConfiscate::withTrashed()->findOrFail($id);
        $suspectToConfiscate->restore(); // Restore soft-deleted suspectToConfiscate$suspectToConfiscate

        return response()->json(['message' => 'SuspectToConfiscate restored successfully.'], 200);
    }

    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        SuspectToConfiscate::withTrashed()->whereIn('id', $request->ids)->restore(); // Restore soft-deleted suspectToConfiscates

        return response()->json(['message' => 'Selected suspectToConfiscates restored successfully.'], 200);
    }

    public function trashed()
    {
        $trashedSuspectToConfiscate = SuspectToConfiscate::onlyTrashed()->get(); // Fetch all soft-deleted suspectToConfiscates

        return response()->json($trashedSuspectToConfiscate, 200);
    }
}
