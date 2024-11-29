<?php

namespace App\Http\Controllers;

use App\Models\UserToTeam;
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
        $userToTeams = UserToTeam::all();

        return Inertia::render('UserToTeam/Index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'user_id' => 'exists:users,id',
            'team_id' => 'exists:teams,id'
        ]);

        $userToTeam = UserToTeam::create($request->all());

        return response()->json($userToTeam, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserToTeam $userToTeam)
    {
        //
        return Inertia::render('UserToTeam/Show',[
            'userToTeam' => $userToTeam->load(['user', 'team'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserToTeam $userToTeam)
    {
        //
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'team_id' => 'required|exists:teams,id'
        ]);

        $userToTeam->update($request->all());

        return response()->json($userToTeam);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserToTeam $userToTeam)
    {
        //
        $userToTeam->delete();

        return response()->json('User to Team deleted successfully');
    }
}
