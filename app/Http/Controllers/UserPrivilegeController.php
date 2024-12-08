<?php

namespace App\Http\Controllers;

use App\Models\UserPrivilege;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
class UserPrivilegeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', new User());


        DB::beginTransaction();

        try {
            // Find the user by the user ID
            $user = User::findOrFail($request->input('userId'));

            // Retrieve the array of role IDs from the request
            $privilegeIds = $request->input('privilegeIds');

            // Sync roles with the user (this will add new roles and remove any removed roles)
            $user->privileges()->sync($privilegeIds);  // Assuming User has a many-to-many relationship with Role

            DB::commit();


            $user->load('privileges');

            return response()->json($user, 200);
        } catch (\Exception $e) {
            DB::rollBack();


            // Return an error response
            return response()->json([
                'message' => 'Failed to assign privileges.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UserPrivilege $userPrivilege)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserPrivilege $userPrivilege)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserPrivilege $userPrivilege)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserPrivilege $userPrivilege)
    {
        //
    }
}
