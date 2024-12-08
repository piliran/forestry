<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use App\Models\Station;
use App\Models\RoleCategory;
use Illuminate\Support\Facades\Gate;
use App\Models\StaffToStation;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    public function index()
    {
        $authUserId = auth()->id(); // Get the authenticated user's ID
        $staffList = [];
        $stations = Station::with('district')->whereNull('deleted_at')->get();

        $staff = Staff::where('user_id', $authUserId)->first();

    if ($staff) {
        // Retrieve the staff list including the station the user belongs to
        $staffList = Staff::with(['level', 'user.roles', 'station.district'])
            ->where('id', $staff->id)
            ->whereNull('deleted_at')
            ->get();
    }
        $users = User::with(['roles', 'district'])
            ->whereNull('deleted_at')
            ->get();

        $roleCategories = RoleCategory::whereNull('deleted_at')->get();

        // Return the data to the view
        return Inertia::render('Staff/Index', [
            'staffList' => $staffList,
            'users' => $users,
            'levels' => $roleCategories,
            'stations' => $stations,
        ]);
    }



    public function store(Request $request)
    {
        Gate::authorize('create', new Staff);

        $request->validate([
            'level_id' => 'required|exists:role_categories,id',
            'user_id' => 'required|exists:users,id',
            'station_id' => 'nullable|exists:stations,id', // Validate station_id if provided
        ]);

        DB::beginTransaction();
        try {
            // Create the staff record
            $data = [
                'level_id' => $request->input('level_id'),
                'user_id' => $request->input('user_id'),
            ];

            $staff = Staff::create($data);

            // If station_id is provided, add entry to StaffToStation
            if ($request->filled('station_id')) {
                StaffToStation::create([
                    'staff_id' => $staff->id,
                    'station_id' => $request->input('station_id'),
                ]);
            }

            // Load related data
            $staff->load(['level', 'user.roles']);

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
        $staff = Staff::findOrFail($request->id);
        Gate::authorize('update', $staff);

        $request->validate([
            'level_id' => 'required|exists:role_categories,id',
            'user_id' => 'required|exists:users,id',
            'station_id' => 'nullable|exists:stations,id', // Validate station_id if provided
        ]);

        DB::beginTransaction();
        try {
            // Prepare data for the update
            $data = [
                'level_id' => $request->input('level_id'),
                'user_id' => $request->input('user_id'),
            ];

            // Update the staff record
            $staff->update($data);

            // Update or insert into StaffToStation if station_id is provided
            if ($request->filled('station_id')) {
                // Check if staff already has an entry in StaffToStation
                $existingEntry = StaffToStation::where('staff_id', $staff->id)->first();

                if ($existingEntry) {
                    // Update the existing entry
                    $existingEntry->update([
                        'station_id' => $request->input('station_id'),
                    ]);
                } else {
                    // Create a new entry
                    StaffToStation::create([
                        'staff_id' => $staff->id,
                        'station_id' => $request->input('station_id'),
                    ]);
                }
            }

            // Load relationships
            $staff->load(['level', 'user.roles']);

            DB::commit();

            return response()->json($staff, 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to update staff record.',
                'error' => $e->getMessage(),
            ], 500);
        }
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
        $trashedStaff = Staff::onlyTrashed()->with(['level', 'user'])->get(); // Fetch all soft-deleted staff records

        return response()->json($trashedStaff, 200);
    }
}
