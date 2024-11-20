<?php

namespace App\Http\Controllers;

use App\Models\UserPermission;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserPermissionsController extends Controller
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
      
        DB::beginTransaction();

        try {
            // Find the user by the user ID
            $user = User::findOrFail($request->input('userId'));

            // Retrieve the array of role IDs from the request
            $permissionIds = $request->input('permissionIds');
      
            // Sync roles with the user (this will add new roles and remove any removed roles)
            $user->permissions()->sync($permissionIds);  // Assuming User has a many-to-many relationship with Role

            DB::commit();

          
            $user->load('permissions');

            return response()->json($user, 200);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error
            Log::error('Error assigning permissions to user: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'message' => 'Failed to assign permissions.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UserPermission $userPermission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserPermission $userPermission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserPermission $userPermission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserPermission $userPermission)
    {
        //
    }
}
