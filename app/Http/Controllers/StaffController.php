<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use App\Models\Station;
use App\Models\RoleCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
    
        // Fetch the staff member for the authenticated user
        $staff = Staff::where('user_id', $userId)->first();
    
        // If no staff found, return an error
        if (!$staff) {
            return redirect()->back()->withErrors([
                'message' => 'The authenticated user is not a registered staff member.',
            ]);
        }
    
        // Query staff list based on the staff's level and station (if present)
        if ($staff->station_id) {
            $staffList = Staff::with(['level', 'user.roles', 'station'])
                ->where('level_id', $staff->level_id)
                ->where('station_id', $staff->station_id)
                ->whereNull('deleted_at')
                ->get();
        } else {
            $staffList = Staff::with(['level', 'user.roles', 'station'])
                ->where('level_id', $staff->level_id)
                ->whereNull('deleted_at')
                ->get();
        }
    
        // Fetch users, role categories, and stations (including relationships)
        $users = User::with(['roles', 'district', 'permissions'])
            ->whereNull('deleted_at')
            ->get();
    
        $roleCategories = RoleCategory::whereNull('deleted_at')->get();
    
        $stations = Station::with('district')
            ->whereNull('deleted_at')
            ->get();
    
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
        $request->validate([
            'level_id' => 'required|exists:role_categories,id',
            'user_id' => 'required|exists:users,id',
            'station_id' => 'nullable|exists:stations,id',
        ]);
    
        DB::beginTransaction();
        try {
            // Prepare data for the staff record
            $data = [
                'level_id' => $request->input('level_id'),
                'user_id' => $request->input('user_id'),
            ];
    
            // Include station_id only if it exists in the request
            if ($request->has('station_id') && $request->filled('station_id')) {
                $data['station_id'] = $request->input('station_id');
            }
    
            // Create the staff record
            $staff = Staff::create($data);
    
        $staff->load(['level', 'user.roles', 'station']);

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
    
        $request->validate([
            'level_id' => 'required|exists:role_categories,id',
            'user_id' => 'required|exists:users,id',
            'station_id' => 'nullable|exists:stations,id',
        ]);
    
        DB::beginTransaction();
        try {
            // Prepare data for the update
            $data = [
                'level_id' => $request->input('level_id'),
                'user_id' => $request->input('user_id'),
            ];
    
            // Check if the incoming request includes 'station_id'
            if ($request->has('station_id') && $request->filled('station_id')) {
                $data['station_id'] = $request->input('station_id');
            } else {
                // If 'station_id' is not provided, set it to null
                $data['station_id'] = null;
            }
    
            // Update the staff record
            $staff->update($data);
    
            // Load relationships
            $staff->load(['level', 'user.roles', 'station']);
    
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
        $trashedStaff = Staff::onlyTrashed()->with(['level', 'user', 'station'])->get(); // Fetch all soft-deleted staff records

        return response()->json($trashedStaff, 200);
    }
}
