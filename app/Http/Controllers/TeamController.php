<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function index()
    {
        // Fetch non-deleted teams with their team lead details
        $teams = Team::whereNull('deleted_at')->with('teamLead')->get();

        return Inertia::render('Teams/Index', [
            'teams' => $teams,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team_lead' => 'required|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $team = Team::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'team_lead' => $request->input('team_lead'),
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team_lead' => 'required|exists:users,id',
        ]);

        $team->update($validated);

        return response()->json($team, 200);
    }

    public function destroy(Team $team)
    {
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
