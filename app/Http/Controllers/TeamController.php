<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\StaffToStation;
use App\Models\Staff;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TeamController extends Controller
{
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
            // Return an empty team array if staff is not assigned to any station
            return Inertia::render('Teams/Index', [
                'teams' => [],
            ]);
        }

        // Use the station ID from StaffToStation to retrieve teams
        $teams = Team::with('station')
            ->where('station_id', $staffToStation->station_id)
            ->whereNull('deleted_at')
            ->get();

        // Return the teams to the view
        return Inertia::render('Teams/Index', [
            'teams' => $teams,
        ]);
    }


    public function store(Request $request)
    {
        Gate::authorize('create', new Staff());

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Get the authenticated user's ID
        $userId = auth()->id();

        // Check if the user exists in the Staff table and fetch the associated station_id
        $staff = Staff::where('user_id', $userId)->first();

        // Check if the staff ID is in StaffToStation
        $staffToStation = StaffToStation::where('staff_id', $staff->id)->first();

        if (!$staffToStation) {
            // Return an empty team array if staff is not assigned to any station
            return Inertia::render('Teams/Index', [
                'teams' => [],
            ]);
        }
        DB::beginTransaction();
        try {
            // Create the team and assign the station_id from the Staff table
            $team = Team::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'station_id' => $staffToStation->station_id, // Extracted station_id
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json($team, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create team.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function update(Request $request, Team $team)
    {
        Gate::authorize('update', $team);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $team->update($validated);
        $team->load('teamLead');

        return response()->json($team, 200);
    }

    public function destroy(Team $team)
    {
        Gate::authorize('delete', $team);

        $team->delete(); // Soft delete

        return response()->json(['message' => 'Team soft-deleted'], 200);
    }

    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        Team::whereIn('id', $validated['ids'])->delete(); // Soft delete

        return response()->json(['message' => 'Teams soft-deleted'], 200);
    }

    public function restore($id)
    {
        $team = Team::withTrashed()->findOrFail($id);
        $team->restore(); // Restore soft-deleted team

        return response()->json(['message' => 'Team restored successfully.'], 200);
    }

    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Team::withTrashed()->whereIn('id', $request->ids)->restore(); // Restore soft-deleted teams

        return response()->json(['message' => 'Selected teams restored successfully.'], 200);
    }

    public function trashed()
    {
        $trashedTeams = Team::onlyTrashed()->get(); // Fetch all soft-deleted teams

        return response()->json($trashedTeams, 200);
    }
}
