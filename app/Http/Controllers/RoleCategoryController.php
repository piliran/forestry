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
        return Inertia::render('roleCategories/Index',[
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
        //
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        RoleCategory::create($request->all());
        return redirect()->route('role-categories.index')->with('success', 'Role Category created successfully.');
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
        //
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $roleCategory->update($request->all());
        return redirect()->route('role-categories.index')->with('success', 'Role Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoleCategory $roleCategory)
    {
        //
        $roleCategory->delete();
        return redirect()->route('role-categories.index')->with('success', 'Role Category deleted successfully.');
    }
}
