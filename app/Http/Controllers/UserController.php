<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use App\Models\Role;
use App\Models\District;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['roles','district'])->get();
        $userRoles = UserRole::with(['user','role'])->get();
        $roles = Role::all();
        $districts = District::all();
        return Inertia::render('User/UserList', [
            'users' => $users,
            'roles' => $roles,
            'userRoles' => $userRoles,
            'districts' => $districts,
            'can' => [
                'createUser' => auth()->user()->hasPermissionTo('create_user'),
                'editUser' => auth()->user()->hasPermissionTo('edit_user'),
                'deleteUser' => auth()->user()->hasPermissionTo('delete_user'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Users/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'gender' => 'nullable|string|max:255',
            'DOB' => 'nullable|date',
            'district_id' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
        
            'position' => 'nullable|string|max:255',
            'national_id' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'account_status' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:255',
       
        ]);

        $this->authorize('create_user');

        $user = User::create([
            'title' => $request->title,
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'DOB' => $request->DOB,
            'district_id' => $request->district_id,
            // 'city' => $request->city,
            // 'country' => $request->country,
            // 'position' => $request->position,
            'national_id' => $request->national_id,
            // 'phone' => $request->phone,
            // 'account_status' => $request->account_status,
            // 'marital_status' => $request->marital_status,
            'password' => bcrypt(12345678),
        ]);

        $user->load(['roles','district']);

        return response()->json($user, 201); // Return the created user
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return Inertia::render('Users/Show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'gender' => 'nullable|string|max:255',
            'DOB' => 'nullable|date',
            'district_id' => 'nullable|integer|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'national_id' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'account_status' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:255',
           
        ]);

        $this->authorize('edit_user');

        $user->update([
            'title' => $request->title,
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'DOB' => $request->DOB,
            'district_id' => $request->district_id,
            // 'city' => $request->city,
            // 'country' => $request->country,
            // 'position' => $request->position,
            'national_id' => $request->national_id,
            'phone' => $request->phone,
            // 'account_status' => $request->account_status,
            // 'marital_status' => $request->marital_status,
            // 'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        $user->load('district');


        return response()->json($user); // Return the updated user
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json('User deleted successfully.');
    }

    /**
     * Bulk delete selected users.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);

        User::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => 'Selected users deleted successfully.',
        ]);
    }
}
