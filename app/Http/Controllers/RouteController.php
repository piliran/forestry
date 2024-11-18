<?php
namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Area;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routes = Route::with('area')->get();
        return Inertia::render('Routes/Index', [
            'routes' => $routes,
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
            'type' => 'required|string|max:255',
        ]);

        $route = Route::create($request->all());

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
            'type' => 'required|string|max:255',
        ]);

        $route->update($request->all());

        return response()->json($route); // Return the updated route
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Route $route)
    {
        $route->delete();

        return response()->json('Route deleted successfully.');
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

        Route::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => 'Selected routes deleted successfully.',
        ]);
    }
}
