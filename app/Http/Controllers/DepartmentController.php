<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;


class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::all();
        return Inertia::render('Admin/Department', [
            'departments' => $departments,
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
        //
        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'website' => 'required|string|max:255',
            'chairperson' => 'required|string|max:255',
            
        ]);

        $department = Department::create($validated);

        return response()->json($department, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        //
        $validated = $request->validate([
            'code' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'chairperson' => 'nullable|string|max:255',
            
        ]);

        $department->update($validated);

        return response()->json($department, 200);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();

        return response()->json(['message' => 'Department deleted successfully'], 200);
    }
    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        Department::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Selected Departments deleted'], 200);
    }
}
