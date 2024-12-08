<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $files = File::with('suspectToConfiscate')->get();

        return Inertia::render('File/Index',[
            'files' => $files,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return a view or JSON if using API-driven approach
        return response()->json(['message' => 'Render create file form.']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', new File());

        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'suspect_to_confiscates_id' => 'required|exists:suspect_to_confiscates,id',
                'file' => 'required|file',
            ]);

            $filePath = $request->file('file')->store('files');

            $file = File::create([
                'suspect_to_confiscates_id' => $validated['suspect_to_confiscates_id'],
                'file' => $filePath,
                'created_by' => auth()->id(),
            ]);

            DB::commit(); // Commit transaction
            return response()->json($file, 201);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return response()->json(['error' => 'Failed to upload file.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, File $file)
    {
        Gate::authorize('update', $file);

        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'suspect_to_confiscates_id' => 'required|exists:suspect_to_confiscates,id',
                'file' => 'nullable|file',
            ]);

            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('files');
                $file->update([
                    'file' => $filePath,
                ]);
            }

            $file->update([
                'suspect_to_confiscates_id' => $validated['suspect_to_confiscates_id'],
            ]);

            DB::commit(); // Commit transaction
            return response()->json($file, 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return response()->json(['error' => 'Failed to update file.'], 500);
        }
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy(File $file)
    {
        Gate::authorize('delete', $file);

        DB::beginTransaction();

        try {
            $file->delete();

            DB::commit(); // Commit transaction
            return response()->json(['message' => 'File deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return response()->json(['error' => 'Failed to delete file.'], 500);
        }
    }

    /**
     * Bulk soft delete selected files.
     */
    public function batchDelete(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:files,id',
            ]);

            File::whereIn('id', $request->ids)->delete();

            DB::commit(); // Commit transaction
            return response()->json(['message' => 'Selected files deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return response()->json(['error' => 'Failed to delete selected files.'], 500);
        }
    }

    /**
     * Restore a soft-deleted file.
     */
    public function restore($id)
    {
        DB::beginTransaction();

        try {
            $file = File::withTrashed()->findOrFail($id);
            $file->restore();

            DB::commit(); // Commit transaction
            return response()->json(['message' => 'File restored successfully.']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return response()->json(['error' => 'Failed to restore file.'], 500);
        }
    }

    /**
     * Bulk restore selected files.
     */
    public function bulkRestore(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:files,id',
            ]);

            File::withTrashed()->whereIn('id', $request->ids)->restore();

            DB::commit(); // Commit transaction
            return response()->json(['message' => 'Selected files restored successfully.']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return response()->json(['error' => 'Failed to restore selected files.'], 500);
        }
    }
}
