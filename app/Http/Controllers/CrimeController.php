<?php
namespace App\Http\Controllers;

use App\Models\Crime;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CrimeController extends Controller
{
    public function index()
    {
        $crimes = Crime::all();

        return Inertia::render('Department/Crimes', [
            'crimes' => $crimes,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'penalty' => 'required|string|max:255',
        ]);

        $crime = Crime::create($validated);

        return response()->json($crime, 201);
    }

    public function update(Request $request, Crime $crime)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'penalty' => 'required|string|max:255',
        ]);

        $crime->update($validated);

        return response()->json($crime, 200);
    }

    public function destroy(Crime $crime)
    {
        $crime->delete();

        return response()->json(['message' => 'Crime deleted'], 200);
    }

    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        Crime::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Crimes deleted'], 200);
    }
}
