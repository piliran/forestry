<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Operation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch non-deleted schedules
        $schedules = Schedule::with('operation')->whereNull('deleted_at')->get();

        $operations = Operation::all();
        return Inertia::render('Schedule/Index', [
            'operations' => $operations,
            'schedules' => $schedules,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', new Schedule());

        $validated = $request->validate([
            'operation_id' => 'required|exists:operations,id',
            'date_of_operation' => 'required|string|max:255',
            'deployment_time' => 'required|string|max:255',
            'withdrawal_time' => 'required|string|max:255',
        ]);

        $schedule = Schedule::create($validated);

        $schedule->load('operation');

        return response()->json($schedule, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        Gate::authorize('update', $schedule);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'operation_id' => 'required|exists:operations,id',
            'date_of_operation' => 'required|string|max:255',
            'deployment_time' => 'required|string|max:255',
            'withdrawal_time' => 'required|string|max:255',
        ]);

        $schedule->update($validated);

        $schedule->load('operation');

        return response()->json($schedule, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        $schedule->load('operation'); // Corrected from 'schedule' to 'operation'
        return Inertia::render('Schedule/Show', [
            'schedule' => $schedule,
        ]);
    }

    /**
     * Soft delete the specified resource.
     */
    public function destroy(Schedule $schedule)
    {
        Gate::authorize('delete', $schedule);

        $schedule->delete(); // Soft delete

        return response()->json(['message' => 'Schedule soft-deleted successfully.'], 200);
    }

    /**
     * Bulk soft delete selected schedules.
     */
    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);

        Schedule::whereIn('id', $validated['ids'])->delete(); // Soft delete

        return response()->json(['message' => 'Selected schedules soft-deleted successfully.'], 200);
    }

    /**
     * Restore a soft-deleted schedule.
     */
    public function restore($id)
    {
        $schedule = Schedule::withTrashed()->findOrFail($id);
        $schedule->restore();

        return response()->json(['message' => 'Schedule restored successfully.'], 200);
    }

    /**
     * Bulk restore selected schedules.
     */
    public function bulkRestore(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);

        Schedule::withTrashed()->whereIn('id', $validated['ids'])->restore();

        return response()->json(['message' => 'Selected schedules restored successfully.'], 200);
    }

    /**
     * Fetch all trashed schedules for review.
     */
    public function trashed()
    {
        $trashedSchedules = Schedule::onlyTrashed()->get();

        return response()->json($trashedSchedules, 200);
    }
}
