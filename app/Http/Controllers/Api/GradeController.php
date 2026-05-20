<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->authorizeResource(Grade::class, 'grade');
    }
    public function index()
    {
        return Grade::with('supervisor')->get();
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|unique:grades,name',
        ]);

        $grade = Auth::user()->supervisedGrades()->create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Grade created successfully',
            'data' => $grade
        ], 201);
    }

    public function show(Grade $grade)
    {
        return $grade->load('supervisor');
    }

    public function update(Request $request, Grade $grade)
    {

        $validated = $request->validate([
            'name' => ['required','string',
            Rule::unique('users')->ignore($grade->id)]
        ]);

        $grade->update($validated);

        return response()->json([
            'message' => 'Grade updated successfully',
            'data' => $grade
        ]);
    }

    public function destroy(Grade $grade)
    {

        $grade->delete();

        return response()->json([
            'message' => 'Grade deleted successfully'
        ]);
    }
}
