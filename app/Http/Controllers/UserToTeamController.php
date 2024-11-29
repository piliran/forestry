<?php

namespace App\Http\Controllers;

use App\Models\UserToTeam;
use App\Models\UserToUserToTeam;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserToTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $userToUserToTeams = UserToTeam::all();

        return Inertia::render('UserToUserToTeam/Index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'user_id' => 'exists:users,id',
            'UserToTeam_id' => 'exists:UserToTeams,id'
        ]);

        $userToUserToTeam = UserToTeam::create($request->all());

        return response()->json($userToUserToTeam, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserToTeam $userToUserToTeam)
    {
        //
        return Inertia::render('UserToUserToTeam/Show',[
            'userToUserToTeam' => $userToUserToTeam->load(['user', 'UserToTeam'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserToTeam $userToUserToTeam)
    {
        //
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'UserToTeam_id' => 'required|exists:UserToTeams,id'
        ]);

        $userToUserToTeam->update($request->all());

        return response()->json($userToUserToTeam);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserToTeam $userToUserToTeam)
    {
        //
        $userToUserToTeam->delete();

        return response()->json('User to UserToTeam deleted successfully');
    }

    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        UserToTeam::whereIn('id', $validated['ids'])->delete(); // Soft delete

        return response()->json(['message' => 'User To Teams soft-deleted'], 200);
    }

    public function restore($id)
    {
        $UserToTeam = UserToTeam::withTrashed()->findOrFail($id);
        $UserToTeam->restore(); // Restore soft-deleted UserToTeam

        return response()->json(['message' => 'User To Team restored successfully.'], 200);
    }

    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        UserToTeam::withTrashed()->whereIn('id', $request->ids)->restore(); // Restore soft-deleted UserToTeams

        return response()->json(['message' => 'Selected UserToTeams restored successfully.'], 200);
    }

    public function trashed()
    {
        $trashedUserToTeams = UserToTeam::onlyTrashed()->get(); // Fetch all soft-deleted UserToTeams

        return response()->json($trashedUserToTeams, 200);
    }
}
