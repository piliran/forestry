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
    
        // Query staff list based on the staff's level (if present)
        if ($staff->level_id) {
            $staffList = Staff::with(['level', 'user.roles'])
                ->where('level_id', $staff->level_id)
                ->whereNull('deleted_at')
                ->get();
        } else {
            $staffList = Staff::with(['level', 'user.roles'])
                ->where('level_id', $staff->level_id)
                ->whereNull('deleted_at')
                ->get();
        }
    
        // Fetch users, role categories (including relationships)
        $users = User::with(['roles', 'district', 'permissions'])
            ->whereNull('deleted_at')
            ->get();
    
        $roleCategories = RoleCategory::whereNull('deleted_at')->get();
    
      
        // Return the data to the view
        return Inertia::render('Staff/Index', [
            'staffList' => $staffList,
            'users' => $users,
            'levels' => $roleCategories,
        ]);
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'level_id' => 'required|exists:role_categories,id',
            'user_id' => 'required|exists:users,id',
        ]);
    
        DB::beginTransaction();
        try {
            // Prepare data for the staff record
            $data = [
                'level_id' => $request->input('level_id'),
                'user_id' => $request->input('user_id'),
            ];
    
            // Create the staff record
            $staff = Staff::create($data);
    
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
    
        $request->validate([
            'level_id' => 'required|exists:role_categories,id',
            'user_id' => 'required|exists:users,id',
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
