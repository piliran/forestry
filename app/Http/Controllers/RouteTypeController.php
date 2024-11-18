<?php
namespace App\Http\Controllers;

use App\Models\RouteType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RouteTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routeTypes = RouteType::all();
        return Inertia::render('RouteTypes/Index', [
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
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        $routeType->update($request->all());

        return response()->json($routeType); // Return the updated route type
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RouteType $routeType)
    {
        $routeType->delete();

        return response()->json('Route Type deleted successfully.');
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

        RouteType::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => 'Selected route types deleted successfully.',
        ]);
    }
}
