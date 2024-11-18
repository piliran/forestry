<?php
namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DistrictController extends Controller
{
    public function index()
    {
        $districts = District::all();

        return Inertia::render('Department/Districts', [
            'districts' => $districts,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'chairperson' => 'required|string|max:255',
        ]);

        $district = District::create($validated);

        return response()->json($district, 201);
    }

    public function update(Request $request, District $district)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'chairperson' => 'required|string|max:255',
        ]);

        $district->update($validated);

        return response()->json($district, 200);
    }

    public function destroy(District $district)
    {
        $district->delete();

        return response()->json(['message' => 'District deleted'], 200);
    }

    public function batchDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array']);
        District::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Districts deleted'], 200);
    }
}
