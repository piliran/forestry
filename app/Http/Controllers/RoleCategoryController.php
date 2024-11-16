<?php

namespace App\Http\Controllers;

use App\Models\RoleCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $roleCategories = RoleCategory::all();
        return Inertia::render('RoleCategories/Index',[
            'roleCategories' => $roleCategories]);
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
        $request->validate([
            'name' => 'required|string|max:255'
        ]);
    
        $category = RoleCategory::create($request->all());
    
        return response()->json($category, 201);  // Return the created category
    }
    /**
     * Display the specified resource.
     */
    public function show(RoleCategory $roleCategory)
    {
        //
        return Inertia::render('RoleCategories/Show',[
            'roleCategory' => $roleCategory
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoleCategory $roleCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoleCategory $roleCategory)
{
    $request->validate([
        'name' => 'required|string|max:255'
    ]);

    $roleCategory->update($request->all());

    return response()->json($roleCategory);  // Return the updated category
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoleCategory $roleCategory)
    {
        //
        $roleCategory->delete();

        return response()->json('Role Category deleted successfully.');
        // return redirect()->route('role-categories.index')->with('success', 'Role Category deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        // Validate the incoming request to ensure 'ids' is an array of integers
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:role_categories,id', // Ensure each id exists in the database
        ]);

        // Delete the categories by their IDs
        RoleCategory::whereIn('id', $request->ids)->delete();

        // Return a response indicating successful deletion
        return response()->json([
            'message' => 'Selected categories deleted successfully.'
        ]);
    }
}
