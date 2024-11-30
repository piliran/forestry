<?php

namespace App\Http\Controllers;

use App\Models\OperationToTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Team;
use App\Models\Operation;
use App\Models\Staff;

class OperationToTeamController extends Controller
{
    public function index()
    {

        $userId = auth()->id();

   
        $staff = Staff::where('user_id', $userId)->first();

        if (!$staff) {
            return redirect()->back()->withErrors([
                'message' => 'The authenticated user is not a registered staff member.',
            ]);
        }

        if (!$staff->station_id) {
            return redirect()->back()->withErrors([
                'message' => 'The authenticated user is not assigned to a station.',
            ]);
        }
   

        $teams = Team::with('station')
        ->where('station_id', $staff->station_id)
        ->whereNull('deleted_at')
        ->get();
        $operations = Operation::with(['type', 'station','route',])->whereNull('deleted_at')->get();

        $operationToTeams = OperationToTeam::with(['operation', 'team'])
            ->whereNull('deleted_at')
            ->get();

            return Inertia::render('TeamOperations/Index', [
                'operationToTeams' => $operationToTeams,
                'teams' => $teams,
                'operations' => $operations,
               
            ]);

        return response()->json($operationToTeams, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'operation_id' => 'required|exists:operations,id',
            'team_id' => 'required|exists:teams,id',
        ]);

        DB::beginTransaction();
        try {
            $operationToTeam = OperationToTeam::create($validated);

            DB::commit();

            return response()->json($operationToTeam, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create operation-to-team mapping.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, OperationToTeam $operationToTeam)
    {
        $validated = $request->validate([
            'operation_id' => 'sometimes|exists:operations,id',
            'team_id' => 'sometimes|exists:teams,id',
        ]);

        $operationToTeam->update($validated);

        return response()->json($operationToTeam, 200);
    }

    public function destroy(OperationToTeam $operationToTeam)
    {
        $operationToTeam->delete(); // Soft delete

        return response()->json(['message' => 'Mapping soft-deleted'], 200);
    }

    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        OperationToTeam::whereIn('id', $validated['ids'])->delete(); // Soft delete

        return response()->json(['message' => 'Mappings soft-deleted'], 200);
    }

    public function restore($id)
    {
        $operationToTeam = OperationToTeam::withTrashed()->findOrFail($id);
        $operationToTeam->restore(); // Restore soft-deleted record

        return response()->json(['message' => 'Mapping restored successfully.'], 200);
    }

    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        OperationToTeam::withTrashed()->whereIn('id', $request->ids)->restore(); // Restore soft-deleted records

        return response()->json(['message' => 'Selected mappings restored successfully.'], 200);
    }

    public function trashed()
    {
        $trashedMappings = OperationToTeam::onlyTrashed()->get(); // Fetch all soft-deleted records

        return response()->json($trashedMappings, 200);
    }
}
