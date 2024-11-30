<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use App\Models\Station;
use App\Models\RoleCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function index()
    {
        // Fetch non-deleted staff with their associated relationships
        $staffList = Staff::with(['level', 'user', 'station'])->whereNull('deleted_at')->get();
        $users = User::with(['roles', 'district', 'permissions'])
        ->whereNull('deleted_at')
        ->get();
        $roleCategories = RoleCategory::whereNull('deleted_at')->get();
        $stations = Station::with('district')->whereNull('deleted_at')->get();



        return Inertia::render('Staff/Index', [
            'staffList' => $staffList,
            'users' => $users,
            'levels' => $roleCategories,
            'stations' => $stations,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_id' => 'required|exists:role_categories,id',
            'user_id' => 'required|exists:users,id',
            'station_id' => 'nullable|exists:stations,id',
        ]);

        DB::beginTransaction();
        try {
            $staff = Staff::create([
                'level_id' => $request->input('level_id'),
                'user_id' => $request->input('user_id'),
                'station_id' => $request->input('station_id'),
            ]);

            DB::commit();

            return response()->json($staff, 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create staff record.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'level_id' => 'required|exists:role_categories,id',
            'user_id' => 'required|exists:users,id',
            'station_id' => 'nullable|exists:stations,id',
        ]);

        $staff->update($validated);

        return response()->json($staff, 200);
    }

    public function destroy(Staff $staff)
    {
        $staff->delete(); // Soft delete

        return response()->json(['message' => 'Staff record soft-deleted'], 200);
    }

    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        Staff::whereIn('id', $validated['ids'])->delete(); // Soft delete

        return response()->json(['message' => 'Staff records soft-deleted'], 200);
    }

    public function restore($id)
    {
        $staff = Staff::withTrashed()->findOrFail($id);
        $staff->restore(); // Restore soft-deleted staff record

        return response()->json(['message' => 'Staff record restored successfully.'], 200);
    }

    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Staff::withTrashed()->whereIn('id', $request->ids)->restore(); // Restore soft-deleted staff records

        return response()->json(['message' => 'Selected staff records restored successfully.'], 200);
    }

    public function trashed()
    {
        $trashedStaff = Staff::onlyTrashed()->with(['level', 'user', 'station'])->get(); // Fetch all soft-deleted staff records

        return response()->json($trashedStaff, 200);
    }
}
