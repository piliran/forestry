<?php

namespace App\Http\Controllers;

use App\Models\RouteType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class RouteTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch non-deleted route types
        $routeTypes = RouteType::whereNull('deleted_at')->get();
        return Inertia::render('Routes/Types', [
            'routeTypes' => $routeTypes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('RouteTypes/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', new RouteType());

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        $routeType = RouteType::create($request->all());

        return response()->json($routeType, 201); // Return the created route type
    }

    /**
     * Display the specified resource.
     */
    public function show(RouteType $routeType)
    {
        return Inertia::render('RouteTypes/Show', [
            'routeType' => $routeType,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RouteType $routeType)
    {
        return Inertia::render('RouteTypes/Edit', [
            'routeType' => $routeType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RouteType $routeType)
    {
        Gate::authorize('update', $routeType);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        $routeType->update($request->all());

        return response()->json($routeType); // Return the updated route type
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy(RouteType $routeType)
    {
        Gate::authorize('delete', $routeType);

        $routeType->delete();  // Soft delete

        return response()->json('Route Type soft-deleted successfully.');
    }

    /**
     * Bulk delete selected route types.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:route_types,id',
        ]);

        RouteType::whereIn('id', $request->ids)->delete();  // Soft delete

        return response()->json([
            'message' => 'Selected route types soft-deleted successfully.',
        ]);
    }

    /**
     * Restore a soft-deleted route type.
     */
    public function restore($id)
    {
        $routeType = RouteType::withTrashed()->findOrFail($id);
        $routeType->restore();  // Restore the soft-deleted route type

        return response()->json(['message' => 'Route Type restored successfully.'], 200);
    }

    /**
     * Restore multiple soft-deleted route types.
     */
    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        RouteType::withTrashed()->whereIn('id', $request->ids)->restore();  // Restore the soft-deleted route types

        return response()->json(['message' => 'Selected route types restored successfully.'], 200);
    }

    /**
     * Fetch trashed route types.
     */
    public function trashed()
    {
        $trashedRouteTypes = RouteType::onlyTrashed()->get();  // Fetch all soft-deleted route types

        return response()->json($trashedRouteTypes, 200);
    }
}
