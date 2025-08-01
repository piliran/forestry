<?php

namespace App\Http\Controllers;

use App\Models\Suspect;
use App\Models\User;
use App\Models\Country;
use App\Models\Crime;
use App\Models\Offense;
use App\Models\Confiscate;
use App\Models\SuspectToConfiscate;
use App\Models\SuspectToOperation;
use App\Models\SuspectToOffense;
use App\Models\OperationToTeam;
use App\Models\File;
use App\Models\District;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class SuspectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch non-deleted suspects
        $suspects = Suspect::with('district')->whereNull('deleted_at')->get();
        $districts = District::whereNull('deleted_at')->get();

        return Inertia::render('Suspects/Index', [
            'suspects' => $suspects,
            'districts' => $districts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $districts = District::whereNull('deleted_at')->get();

        $confiscates = Confiscate::whereNull('deleted_at')->get();
        $offenses = Offense::whereNull('deleted_at')->get();
        $operationToTeams = OperationToTeam::with(['operation', 'team'])
            ->whereNull('deleted_at')
            ->get();

        return Inertia::render('Suspects/Create', [
            'districts' => $districts,
            'crimes' => $offenses,
            'confiscates' => $confiscates,
            'teamOperations' => $operationToTeams,
            'operationId' => number_format( $id),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    Gate::authorize('create', new Suspect());

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'national_id' => 'required|string|max:255|unique:suspects,national_id',
        'district_id' => 'required|exists:districts,id',
        'operation_id' => 'required|exists:operations,id',
        'village' => 'required|string|max:255',
        'age' => 'nullable|string',
        'sex' => 'required|string|max:255',
        'TA' => 'required|string|max:255',
        'suspect_photo_path' => 'nullable|image|max:2048',
        'confiscates' => 'sometimes|array',
        'offenses' => 'sometimes|array',
        'confiscates.*.id' => 'exists:confiscates,id',
        'confiscates.*.quantity' => 'nullable|string',
        'confiscates.*.files' => 'array',
        'confiscates.*.files.*' => 'file|mimes:jpg,jpeg,png|max:2048',
    ]);

    DB::beginTransaction();
    try {
        if ($request->hasFile('suspect_photo_path')) {
            $validated['suspect_photo_path'] = $request
                ->file('suspect_photo_path')
                ->store('suspects/photos', 'public');
        }

        $suspect = Suspect::create([
            'name' => $validated['name'],
            'national_id' => $validated['national_id'],
            'district_id' => $validated['district_id'],
            'village' => $validated['village'],
            'sex' => $validated['sex'],
            'age' => $validated['age'],
            'TA' => $validated['TA'],
            'suspect_photo_path' => $validated['suspect_photo_path'] ?? null,
            'created_by' => auth()->id(),
        ]);

        if ($request->has('offenses')) {
            foreach ($validated['offenses'] as $offense) {
                SuspectToOffense::create([
                    'suspect_id' => $suspect->id,
                    'offense_id' => $offense['id'],
                ]);
            }
        }

        if ($request->has('confiscates')) {
            foreach ($validated['confiscates'] as $confiscate) {
                $suspectToConfiscate = SuspectToConfiscate::create([
                    'suspect_id' => $suspect->id,
                    'confiscate_id' => $confiscate['id'],
                    'quantity' => $confiscate['quantity'],
                ]);

                if (!empty($confiscate['files'])) {
                    foreach ($confiscate['files'] as $file) {
                        $path = $file->store('confiscates/files', 'public');
                        File::create([
                            'suspect_to_confiscates_id' => $suspectToConfiscate->id,
                            'file' => $path,
                            'created_by' => auth()->id(),
                        ]);
                    }
                }
            }
        }

        SuspectToOperation::create([
            'suspect_id' => $suspect->id,
            'operation_id' => $validated['operation_id'],
        ]);

        DB::commit();

        // Immediate return to ensure no further execution
        return response()->json([
            'message' => 'Suspect created successfully',
            'suspect_id' => $suspect->id,
        ], 200);

    } catch (\Throwable $th) {
        DB::rollBack();

        // Log the error for debugging purposes
        Log::error('Error creating suspect', [
            'error' => $th->getMessage(),
            'trace' => $th->getTraceAsString(),
        ]);

        // Immediate return after handling the error
        return response()->json([
            'message' => 'Failed to create record',
            'error' => $th->getMessage(), // Use a generic error in production
        ], 500);
    }
}



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $suspect= Suspect::find($id);
        Log::info( $suspect);
        $suspect->load( ['district','confiscates','operations.station','operations.route', 'offenses','files']);
        return Inertia::render('Suspects/Show', [
            'suspect' => $suspect,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Suspect $suspect)
    {
        return Inertia::render('Admin/EditSuspect', [
            'suspect' => $suspect,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {



        $suspect = Suspect::findOrFail($request->id);
        Gate::authorize('update', $suspect);


        if (auth()->user()->cannot('update',  $suspect)) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'national_id' => 'required|string|max:255|unique:suspects,national_id,' . $suspect->id,
            'district_id' => 'required|exists:districts,id',
            'village' => 'required|string|max:255',
            'TA' => 'required|string|max:255',
            'suspect_photo_path' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('suspect_photo_path')) {
            if ($suspect->suspect_photo_path) {
                Storage::disk('public')->delete($suspect->suspect_photo_path);
            }

            $validated['suspect_photo_path'] = $request
                ->file('suspect_photo_path')
                ->store('suspects/photos', 'public');
        }

        $suspect->update([
            'name' => $validated['name'],
            'national_id' => $validated['national_id'],
            'district_id' => $validated['district_id'],
            'village' => $validated['village'],
            'TA' => $validated['TA'],
            'suspect_photo_path' => $validated['suspect_photo_path'] ?? $suspect->suspect_photo_path,
        ]);

        $suspect->load('district');

        return response()->json($suspect, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Suspect $suspect)
    {
        Gate::authorize('delete', $suspect);


        if (auth()->user()->cannot('delete', $suspect)) {
            abort(403, 'Unauthorized action.');
        }


        // Perform soft delete by setting 'deleted_at'
        $suspect->delete();
        return response()->json(['message' => 'Suspect deleted successfully'], 200);
    }

    /**
     * Batch delete specified resources from storage.
     */
    public function batchDelete(Request $request)
    {
        if (auth()->user()->cannot('batchDelete', Suspect::class)) {
            abort(403, 'Unauthorized action.');
        }


        $validated = $request->validate(['ids' => 'required|array']);
        Suspect::whereIn('id', $validated['ids'])->delete();
        return response()->json(['message' => 'Selected suspects deleted successfully'], 200);
    }

    /**
     * Update a suspect's details.
     */
    public function updateSuspect(Request $request)
    {


        $suspect = Suspect::findOrFail($request->id);
        Gate::authorize('update', $suspect);


        if (auth()->user()->cannot('update', $suspect)) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'national_id' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
            'village' => 'required|string|max:255',
            'TA' => 'required|string|max:255',
            'suspect_photo_path' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('suspect_photo_path')) {
            if ($suspect->suspect_photo_path) {
                Storage::disk('public')->delete($suspect->suspect_photo_path);
            }

            $validated['suspect_photo_path'] = $request
                ->file('suspect_photo_path')
                ->store('suspects/photos', 'public');
        } else {
            $validated['suspect_photo_path'] = $suspect->suspect_photo_path;
        }

        $suspect->update([
            'name' => $validated['name'],
            'national_id' => $validated['national_id'],
            'district_id' => $validated['district_id'],
            'village' => $validated['village'],
            'TA' => $validated['TA'],
        ]);

        if ($request->hasFile('suspect_photo_path')) {
            $suspect->update(['suspect_photo_path' => $validated['suspect_photo_path']]);
        }

        $suspect->load('district');

        $suspect->suspect_photo_url = $suspect->suspect_photo_path
            ? asset('storage/' . $suspect->suspect_photo_path)
            : null;

        return response()->json($suspect, 200);
    }
}






