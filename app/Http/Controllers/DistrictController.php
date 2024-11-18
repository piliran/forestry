<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $districts = District::all();
        return Inertia::render('Districts/Index', [
            'districts' => $districts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Districts/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'chairperson' => 'required|string|max:255',
        ]);

        $district = District::create($request->all());

        return response()->json($district, 201); // Return the created district
    }

    /**
     * Display the specified resource.
     */
    public function show(District $district)
    {
        return Inertia::render('Districts/Show', [
            'district' => $district,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(District $district)
    {
        return Inertia::render('Districts/Edit', [
            'district' => $district,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, District $district)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'chairperson' => 'required|string|max:255',
        ]);

        $district->update($request->all());

        return response()->json($district); // Return the updated district
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(District $district)
    {
        $district->delete();

        return response()->json('District deleted successfully.');
    }

    /**
     * Bulk delete selected districts.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:districts,id',
        ]);

        District::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => 'Selected districts deleted successfully.',
        ]);
    }
}
