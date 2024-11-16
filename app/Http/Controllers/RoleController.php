<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RoleCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('category')->get();

        return Inertia::render('Roles/Index', [
            'roles' => $roles,
        ]);
    }

    // Show the form for creating a new resource
    public function create()
    {
        
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role_category_id' => 'required|exists:role_categories,id',
        ]);

        Role::create($request->all());

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    // Display the specified resource
    public function show(Role $role)
    {
        return Inertia::render('Roles/Show', [
            'role' => $role->load('category'),
        ]);
    }

    // Show the form for editing the specified resource
    public function edit(Role $role)
    {
        
    }

    // Update the specified resource in storage
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role_category_id' => 'required|exists:role_categories,id',
        ]);

        $role->update($request->all());

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    // Remove the specified resource from storage
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
