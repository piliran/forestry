<?php

namespace App\Http\Controllers;

use App\Models\UserToTeam;
use App\Models\Team;
use App\Models\StaffToStation;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class UserToTeamController extends Controller
{
    /**
     * Display a listing of users and their associated teams.
     */
    public function index()
    {

        $userId = auth()->id(); // Get the authenticated user's ID

        // Check if the user exists in Staff
        $staff = Staff::where('user_id', $userId)->first();

        if (!$staff) {
            return redirect()->back()->withErrors([
                'message' => 'The authenticated user is not a registered staff member.',
            ]);
        }

        // Check if the staff ID is in StaffToStation
        $staffToStation = StaffToStation::where('staff_id', $staff->id)->first();

        if (!$staffToStation) {
            return Inertia::render('Teams/Members', [
                'teamMembers' => [],
                'teams' => [],
                'staffList' => [],
            ]);
        }

        $teamMembers = UserToTeam::with(['staff.user', 'team'])
            ->whereNull('deleted_at')
            ->get();

            $teams = Team::with('station')
            ->where('station_id', $staffToStation->station_id)
            ->whereNull('deleted_at')
            ->get();

            $staffList = Staff::with(['level', 'user.roles', 'station'])

            ->where('level_id', $staff->level_id)
            ->whereNull('deleted_at')
            ->get();

        return Inertia::render('Teams/Members', [
            'teamMembers' => $teamMembers,
            'teams' => $teams,
            'staffList' => $staffList,
        ]);
    }

    /**
     * Store a newly created association in the database.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', new UserToTeam());

        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'team_id' => 'required|exists:teams,id',
            'is_team_lead' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $userToTeam = UserToTeam::create([
                'staff_id' => $validated['staff_id'],
                'team_id' => $validated['team_id'],
                'is_team_lead' => $validated['is_team_lead'] ?? 0,
            ]);

            DB::commit();
            $userToTeam->load(['staff.user', 'team']);
            return response()->json($userToTeam, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create association.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(UserToTeam $userToTeam)
    {
        $userToTeam->load(['staff', 'team']);
        return Inertia::render('UserToTeam/Show',[
            'userToTeam' => $userToTeam
        ]);
    }

    /**
     * Update the specified association in the database.
     */
    public function update(Request $request, UserToTeam $userToTeam)
    {
        Gate::authorize('update', $userToTeam);

        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'team_id' => 'required|exists:teams,id',
            'is_team_lead' => 'nullable|boolean',
        ]);

        $userToTeam->update($validated);
        $userToTeam->load(['staff.user', 'team']);

        return response()->json($userToTeam, 200);
    }

    /**
     * Soft delete the specified association.
     */
    public function destroy(UserToTeam $userToTeam)
    {
        Gate::authorize('delete', $userToTeam);

        $userToTeam->delete();

        return response()->json(['message' => 'Association soft-deleted'], 200);
    }

    /**
     * Batch soft delete associations.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        UserToTeam::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Associations soft-deleted'], 200);
    }

    /**
     * Restore a soft-deleted association.
     */
    public function restore($id)
    {
        $userToTeam = UserToTeam::withTrashed()->findOrFail($id);
        $userToTeam->restore();

        return response()->json(['message' => 'Association restored successfully.'], 200);
    }

    /**
     * Bulk restore soft-deleted associations.
     */
    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        UserToTeam::withTrashed()->whereIn('id', $request->ids)->restore();

        return response()->json(['message' => 'Selected associations restored successfully.'], 200);
    }

    /**
     * Display all soft-deleted associations.
     */
    public function trashed()
    {
        $trashed = UserToTeam::onlyTrashed()->with(['staff', 'team'])->get();

        return response()->json($trashed, 200);
    }
}
