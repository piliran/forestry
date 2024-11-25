<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Area;
use App\Models\RouteType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch non-deleted routes
        $routes = Route::whereNull('deleted_at')->with(['area','routeType'])->get();
        $areas = Area::all();
        $routeTypes = RouteType::all();
        return Inertia::render('Department/RouteList', [
            'routes' => $routes,
            'areas' => $areas,
            'routeTypes' => $routeTypes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $areas = Area::all();
        return Inertia::render('Routes/Create', [
            'areas' => $areas,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'route_type_id' => 'required|exists:route_types,id',
        ]);

        $route = Route::create($request->all());
        $route->load('area');
        $route->load('routeType');

        return response()->json($route, 201); // Return the created route
    }

    /**
     * Display the specified resource.
     */
    public function show(Route $route)
    {
        return Inertia::render('Routes/Show', [
            'route' => $route->load('area'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Route $route)
    {
        $areas = Area::all();
        return Inertia::render('Routes/Edit', [
            'route' => $route,
            'areas' => $areas,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Route $route)
    {
        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'route_type_id' => 'required|exists:routeTypes,id',
        ]);
        $route->update($request->all());
        $route->load('area');
        $route->load('routeType');
        
        return response()->json($route); // Return the updated route
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy(Route $route)
    {
        $route->delete();  // Soft delete

        return response()->json('Route soft-deleted successfully.');
    }

    /**
     * Bulk delete selected routes.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:routes,id',
        ]);

        Route::whereIn('id', $request->ids)->delete();  // Soft delete

        return response()->json([
            'message' => 'Selected routes soft-deleted successfully.',
        ]);
    }

    /**
     * Restore a soft-deleted route.
     */
    public function restore($id)
    {
        $route = Route::withTrashed()->findOrFail($id);
        $route->restore();  // Restore the soft-deleted route

        return response()->json(['message' => 'Route restored successfully.'], 200);
    }

    /**
     * Restore multiple soft-deleted routes.
     */
    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Route::withTrashed()->whereIn('id', $request->ids)->restore();  // Restore the soft-deleted routes

        return response()->json(['message' => 'Selected routes restored successfully.'], 200);
    }

    /**
     * Fetch trashed routes.
     */
    public function trashed()
    {
        $trashedRoutes = Route::onlyTrashed()->get();  // Fetch all soft-deleted routes

        return response()->json($trashedRoutes, 200);
    }
}
