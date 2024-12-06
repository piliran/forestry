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
        // Fetch non-deleted departments
        $departments = Department::whereNull('deleted_at')->get();

        return Inertia::render('Department/Index', [
            'departments' => $departments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'website' => 'required|string|max:255',
            'contact_person' => 'required|exists:users,id',
        ]);

        $department = Department::create($validated);

        return response()->json($department, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'contact_person' => 'exists:users,id',
        ]);

        $department->update($validated);

        return response()->json($department, 200);
    }

    /**
     * Soft delete the specified resource.
     */
    public function destroy(Department $department)
    {
        $department->delete(); // Soft delete

        return response()->json(['message' => 'Department soft-deleted successfully.'], 200);
    }

    /**
     * Bulk soft delete selected departments.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);

        Department::whereIn('id', $validated['ids'])->delete(); // Soft delete

        return response()->json(['message' => 'Selected departments soft-deleted successfully.'], 200);
    }

    /**
     * Restore a soft-deleted department.
     */
    public function restore($id)
    {
        $department = Department::withTrashed()->findOrFail($id);
        $department->restore();

        return response()->json(['message' => 'Department restored successfully.'], 200);
    }

    /**
     * Bulk restore selected departments.
     */
    public function bulkRestore(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);

        Department::withTrashed()->whereIn('id', $validated['ids'])->restore();

        return response()->json(['message' => 'Selected departments restored successfully.'], 200);
    }

    /**
     * Fetch all trashed departments for review.
     */
    public function trashed()
    {
        $trashedDepartments = Department::onlyTrashed()->get();

        return response()->json($trashedDepartments, 200);
    }
}
