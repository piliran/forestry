<?php

namespace App\Http\Controllers;

use App\Models\Funder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class FunderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $funders = Funder::all();

        return Inertia::render('Funders/Index', [
            'funders' => $funders,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Funders/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', new Funder());

        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:funders,email',
                'organization' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string|max:255',
            ]);

            $funder = Funder::create($validated);

            DB::commit(); // Commit transaction
            return response()->json($funder, 201);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return response()->json(['error' => 'Failed to create funder.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Funder $funder)
    {
        Gate::authorize('update', $funder);

        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:funders,email,' . $funder->id,
                'organization' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string|max:255',
            ]);

            $funder->update($validated);

            DB::commit(); // Commit transaction
            return response()->json($funder, 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return response()->json(['error' => 'Failed to update funder.'], 500);
        }
    }

    public function show(Funder $funder)
    {
        $funder->load('operations');
        return Inertia::render('Funder/Show',[
            'funder' => $funder
        ]);
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy(Funder $funder)
    {
        Gate::authorize('delete', $funder);

        DB::beginTransaction();

        try {
            $funder->delete();

            DB::commit(); // Commit transaction
            return response()->json(['message' => 'Funder deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return response()->json(['error' => 'Failed to delete funder.'], 500);
        }
    }

    /**
     * Bulk soft delete selected funders.
     */
    public function batchDelete(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:funders,id',
            ]);

            Funder::whereIn('id', $request->ids)->delete();

            DB::commit(); // Commit transaction
            return response()->json(['message' => 'Selected funders deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return response()->json(['error' => 'Failed to delete selected funders.'], 500);
        }
    }

    /**
     * Restore a soft-deleted funder.
     */
    public function restore($id)
    {
        DB::beginTransaction();

        try {
            $funder = Funder::withTrashed()->findOrFail($id);
            $funder->restore();

            DB::commit(); // Commit transaction
            return response()->json(['message' => 'Funder restored successfully.']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return response()->json(['error' => 'Failed to restore funder.'], 500);
        }
    }

    /**
     * Bulk restore selected funders.
     */
    public function bulkRestore(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:funders,id',
            ]);

            Funder::withTrashed()->whereIn('id', $request->ids)->restore();

            DB::commit(); // Commit transaction
            return response()->json(['message' => 'Selected funders restored successfully.']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return response()->json(['error' => 'Failed to restore selected funders.'], 500);
        }
    }
}
